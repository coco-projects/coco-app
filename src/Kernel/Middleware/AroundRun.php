<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\Kernel\Middleware;

    use Coco\cocoApp\CocoApp;
    use Coco\cocoApp\Kernel\CoreEvents\ProcessorRunAfterEvent;
    use Coco\cocoApp\Kernel\CoreEvents\ProcessorRunBeforeEvent;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Server\MiddlewareInterface;
    use Psr\Http\Server\RequestHandlerInterface;

    class AroundRun implements MiddlewareInterface
    {
        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
        {
            CocoApp::getInstance()->event->triggerEvent(new ProcessorRunBeforeEvent(CocoApp::getInstance()));

            $reponse = $handler->handle($request);

            CocoApp::getInstance()->event->triggerEvent(new ProcessorRunAfterEvent(CocoApp::getInstance()));

            return $reponse;
        }
    }
