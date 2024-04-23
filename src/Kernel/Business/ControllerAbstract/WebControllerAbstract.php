<?php

    namespace Coco\cocoApp\Kernel\Business\ControllerAbstract;

    use Coco\cocoApp\Kernel\Business\ControllerWrapper\WebControllerWrapper;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Slim\App;
    use Slim\Exception\HttpBadRequestException;
    use Slim\Interfaces\RouteInterface;
    use Slim\Interfaces\RouteParserInterface;
    use Slim\Routing\RouteContext;
    use Slim\Routing\RoutingResults;
    use Slim\Views\Twig;
    use Twig\Loader\FilesystemLoader;

    abstract class WebControllerAbstract extends ControllerAbstract
    {
        public ?WebControllerWrapper $wrapper;
        public ?RouteContext         $routeContext;
        public ?RouteInterface       $route;
        public ?RouteParserInterface $routeParser;
        public ?RoutingResults       $routingResults;
        public Request               $request;
        public Response              $response;
        public App                   $slimApp;
        public array                 $args         = [];
        public string                $templatePath = '';
        public string                $staticPath   = '';

        public function __construct(WebControllerWrapper $wrapper)
        {
            $this->wrapper = $wrapper;
            $this->cocoApp = $this->wrapper->cocoApp;
        }

        public function init(): void
        {
            $this->request  = $this->wrapper->request;
            $this->response = $this->cocoApp->response = $this->wrapper->response;
            $this->args     = $this->wrapper->args;

            $this->slimApp      = $this->cocoApp->slim;
            $this->routeContext = $this->cocoApp->routeContext = RouteContext::fromRequest($this->request);
            $this->route        = $this->cocoApp->route = $this->routeContext->getRoute();

            $this->routeParser    = $this->routeContext->getRouteParser();
            $this->routingResults = $this->routeContext->getRoutingResults();

            $platform = 'pc';
            if ($this->cocoApp->config->base->enable_mobile_template && $this->cocoApp->envDetector->client->isMobile())
            {
                $platform = 'mobile';
            }

            $this->templatePath = implode(DIRECTORY_SEPARATOR, [
                $this->getInfo()->getTemplateDir(),
                $platform,
            ]);

            //todo cdn 部署
            $this->staticPath = implode(DIRECTORY_SEPARATOR, [
                $this->getInfo()->getTemplateUrl(),
                $platform,
                'statics',
            ]);
        }

        protected function getFormData(): object|array
        {
            return $this->request->getParsedBody();
        }

        protected function resolveArg(string $name): mixed
        {
            if (!isset($this->args[$name]))
            {
                throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
            }

            return $this->args[$name];
        }

        /*
         * -------------------------------------------------------------------------
         */
        protected function viewString(?string $templateString, $data = [], int $statusCode = 200): Response
        {
            $view = new Twig(new FilesystemLoader());

            $html = $view->fetchFromString($templateString, $data);

            return $this->respondHtml($html,$statusCode);
        }

        protected function viewForClosure(?string $templateName = null, $data = []): Twig
        {
            if (empty($templateName))
            {
                $templateName = md5($this->route->getPattern()) . '.twig';
            }

            $templatePath = $this->templatePath . DIRECTORY_SEPARATOR . 'closure';

            if (!is_dir($templatePath))
            {
                mkdir($templatePath, 777, true);
            }

            $templateFullPath = $templatePath . DIRECTORY_SEPARATOR . $templateName;

            if (!is_file($templateFullPath))
            {
                file_put_contents($templateFullPath, "<h3>$templateFullPath</h3>");
            }

            $view = Twig::create($templatePath, [
                'debug'            => $this->cocoApp->config->base->app_debug,
                'charset'          => 'UTF-8',
                'strict_variables' => false,
                'autoescape'       => 'html',
                'cache'            => false,
                'auto_reload'      => null,
                'optimizations'    => -1,
            ]);

            $view->render($this->response, $templateName, $data);

            return $view;
        }

        protected function view(?string $templateName = null, $data = []): Twig
        {
            if (empty($templateName))
            {
                $templateName = md5($this->route->getPattern()) . '.twig';
            }

            preg_match('#[^\\\\]+$#i', static::class, $result);
            $templatePath = implode(DIRECTORY_SEPARATOR, [
                $this->templatePath,
                $result[0],
            ]);

            if (!is_dir($templatePath))
            {
                mkdir($templatePath, 777, true);
            }

            $templateFullPath = $templatePath . DIRECTORY_SEPARATOR . $templateName;

            if (!is_file($templateFullPath))
            {
                file_put_contents($templateFullPath, "<h3>$templateFullPath</h3>");
            }

            $view = Twig::create($templatePath, [
                'debug'            => $this->cocoApp->config->base->app_debug,
                'charset'          => 'UTF-8',
                'strict_variables' => false,
                'autoescape'       => 'html',
                'cache'            => false,
                'auto_reload'      => null,
                'optimizations'    => -1,
            ]);

            $view->render($this->response, $templateName, $data);

            return $view;
        }

        /*
         * -------------------------------------------------------------------------
         */
        protected function respondJson(array $payload, int $statusCode = 200): Response
        {
            $json = json_encode($payload, JSON_PRETTY_PRINT);
            $this->response->getBody()->write($json);

            return $this->response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
        }

        protected function respondHtml(string $payload, int $statusCode = 200): Response
        {
            $this->response->getBody()->write($payload);

            return $this->response->withHeader('Content-Type', 'text/html')->withStatus($statusCode);
        }

        protected function redirect(string $destination, array $queryParams = []): ResponseInterface
        {
            if ($queryParams)
            {
                $destination = sprintf('%s?%s', $destination, http_build_query($queryParams));
            }

            return $this->response->withStatus(302)->withHeader('Location', $destination);
        }

        protected function redirectFor(string $routeName, array $data = [], array $queryParams = []): ResponseInterface
        {
            return $this->redirect($this->routeParser->urlFor($routeName, $data, $queryParams));
        }

    }