<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\KernelModule\Http\Middleware;

    use Coco\cocoApp\Kernel\CocoApp;
    use Coco\cocoApp\KernelModule\Events\ProcessorRunAfterEvent;
    use Coco\cocoApp\KernelModule\Events\ProcessorRunBeforeEvent;
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
