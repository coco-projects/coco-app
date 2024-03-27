<?php

    namespace Coco\cocoApp\Kernel\Business\ControllerAbstract;

    use \Coco\cocoApp\CocoApp;
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

    abstract class WebControllerAbstract extends ControllerAbstract
    {
        public ?WebControllerWrapper $wrapper;
        public ?CocoApp              $cocoApp;
        public ?App                  $slimApp;
        public ?RouteContext         $routeContext;
        public ?RouteInterface       $route;
        public ?RouteParserInterface $routeParser;
        public ?RoutingResults       $routingResults;
        public Request               $request;
        public Response              $response;
        public array                 $args = [];

        public function __construct(WebControllerWrapper $wrapper)
        {
            $this->wrapper = $wrapper;
        }

        public function init(): void
        {
            $this->cocoApp = $this->wrapper->cocoApp;

            $this->request  = $this->wrapper->request;
            $this->response = $this->cocoApp->response = $this->wrapper->response;
            $this->args     = $this->wrapper->args;

            $this->slimApp      = $this->cocoApp->slim;
            $this->routeContext = $this->cocoApp->routeContext = RouteContext::fromRequest($this->request);
            $this->route        = $this->cocoApp->route = $this->routeContext->getRoute();

            $this->routeParser    = $this->routeContext->getRouteParser();
            $this->routingResults = $this->routeContext->getRoutingResults();
        }

        public function getFormData(): object|array
        {
            return $this->request->getParsedBody();
        }

        public function resolveArg(string $name): mixed
        {
            if (!isset($this->args[$name]))
            {
                throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
            }

            return $this->args[$name];
        }

        public function respondJson(array $payload, int $statusCode = 200): Response
        {
            $json = json_encode($payload, JSON_PRETTY_PRINT);
            $this->response->getBody()->write($json);

            return $this->response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
        }

        public function respondHtml(string $payload, int $statusCode = 200): Response
        {
            $this->response->getBody()->write($payload);

            return $this->response->withHeader('Content-Type', 'text/html')->withStatus($statusCode);
        }

        public function redirect(ResponseInterface $response, string $destination, array $queryParams = []): ResponseInterface
        {
            if ($queryParams)
            {
                $destination = sprintf('%s?%s', $destination, http_build_query($queryParams));
            }

            return $response->withStatus(302)->withHeader('Location', $destination);
        }

        public function redirectFor(ResponseInterface $response, string $routeName, array $data = [], array $queryParams = []): ResponseInterface
        {
            return $this->redirect($response, $this->routeParser->urlFor($routeName, $data, $queryParams));
        }

    }