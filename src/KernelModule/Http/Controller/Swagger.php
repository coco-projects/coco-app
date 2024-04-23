<?php

    namespace Coco\cocoApp\KernelModule\Http\Controller;

    use OpenApi\Attributes as OA;
    use Slim\Exception\HttpNotFoundException;

    #[OA\Info(version: "0.1", title: "API")]
    class Swagger extends BaseController
    {
        public function page()
        {
            $request      = $this->request;
            $response     = $this->response;
            $args         = $this->args;
            $routeContext = $this->routeContext;
            $route        = $this->route;
            $cocoApp      = $this->cocoApp;
            $slim         = $this->slimApp;

            if (empty($route))
            {
                throw new HttpNotFoundException($request);
            }

            $name      = $route->getName();
            $groups    = $route->getGroups();
            $methods   = $route->getMethods();
            $arguments = $route->getArguments();

            $data = [
                'staticPath' => $this->staticPath,
                'api_url'    => $slim->getRouteCollector()->getRouteParser()->urlFor('coco_api_'),
            ];

            $this->view('coco_api.twig', $data);

            return $response;

        }

        #[OA\Get(path: '/coco_api_')]
        #[OA\Response(response: '200', description: 'swagger api ')]
        public function api()
        {

            $request      = $this->request;
            $response     = $this->response;
            $args         = $this->args;
            $routeContext = $this->routeContext;
            $route        = $this->route;
            $cocoApp      = $this->cocoApp;
            $slim         = $this->slimApp;

            if (empty($route))
            {
                throw new HttpNotFoundException($request);
            }

            $name      = $route->getName();
            $groups    = $route->getGroups();
            $methods   = $route->getMethods();
            $arguments = $route->getArguments();

            $controllerDirs = [];

            foreach ($this->cocoApp->getBooters() as $k => $booter)
            {
                $controllerDirs[] = $booter->getAppInfo()->getControllerDir();
            }
            $openapi = \OpenApi\Generator::scan($controllerDirs);

//            $response = $response->withHeader('Content-Type', 'application/x-yaml');
//            $response->getBody()->write(file_get_contents('swagger/example.yaml'));
//            $response->getBody()->write($openapi->toYaml());

            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write($openapi->toJSON());

            return $response;
        }
    }
