<?php

    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;
    use Coco\cocoApp\Kernel\CocoApp;
    use Coco\cocoApp\Kernel\CocoAppConsts;
    use Coco\cocoApp\KernelModule\Listeners\CallableListener;
    use Coco\cocoApp\KernelModule\Listeners\EchoExceptionMsgListener;
    use Coco\cocoApp\KernelModule\Listeners\EchoLoggerListener;
    use Coco\cocoApp\KernelModule\Listeners\SystemInitListener;
    use Coco\cocoApp\KernelModule\Listeners\ThrowExceptionMsgListener;

    $app = CocoApp::getInstance();

    return [

        CocoAppConsts::CORE_SYSTEM_INIT_START => [
            new CallableListener(function(CoreEventAbstract $coreEventAbstract) {

                /**
                 * @var CallableListener $this
                 */
                $this->cocoApp->timer->start();

                if (session_status() !== PHP_SESSION_ACTIVE)
                {
                    session_start();
                }
            }),
//            new EchoLoggerListener(),
            new SystemInitListener(),
        ],

        CocoAppConsts::CORE_CONSOLE_INIT_START => [

//            new EchoLoggerListener(),
        ],

        CocoAppConsts::CORE_CONSOLE_INIT_END => [

//            new EchoLoggerListener(),
        ],


        CocoAppConsts::CORE_WEBSITESERVER_INIT_START => [

//            new EchoLoggerListener(),
            new CallableListener(function(CoreEventAbstract $coreEventAbstract) {

                /**
                 * @var CallableListener $this
                 */

                $this->cocoApp->slim->addRoutingMiddleware();
            }),
        ],

        CocoAppConsts::CORE_WEBSITESERVER_INIT_END => [

//            new EchoLoggerListener(),
            new CallableListener(function(CoreEventAbstract $coreEventAbstract) {

                /**
                 * @var CallableListener $this
                 */

//                $this->cocoApp->slim->addErrorMiddleware(true, true, true);
                $this->cocoApp->slim->add(new Zeuxisoo\Whoops\Slim\WhoopsMiddleware([
                    'enable' => true,
                    'editor' => 'phpstorm',
                    'title'  => 'slim project',
                ]));
            }),
        ],

        CocoAppConsts::CORE_SYSTEM_INIT_END => [
            new CallableListener(function(CoreEventAbstract $coreEventAbstract) {

                /**
                 * @var CallableListener $this
                 */

                $this->cocoApp->setAppDebug(!!$this->cocoApp->config->base->app_debug);
            }),
        ],

        CocoAppConsts::CORE_PROCESS_ON_START => [
//            new EchoLoggerListener(),
        ],

        CocoAppConsts::CORE_PROCESS_RUN_BEFORE => [
//            new EchoLoggerListener(),

        ],

        CocoAppConsts::CORE_PROCESS_RUN => [
//            new EchoLoggerListener(),

        ],

        CocoAppConsts::CORE_PROCESS_RUN_AFTER => [
//            new EchoLoggerListener(),

        ],

        CocoAppConsts::CORE_PROCESS_ON_DONE => [
//            new EchoLoggerListener(),
            new CallableListener(function(CoreEventAbstract $coreEventAbstract) {

                /**
                 * @var CallableListener $this
                 */
                $this->cocoApp->timer->mark(CocoAppConsts::CORE_PROCESS_ON_DONE);
            }),
        ],

        CocoAppConsts::CORE_PROCESS_ON_CATCH => [
            new EchoExceptionMsgListener(),
//            new ThrowExceptionMsgListener(),
//            new EchoLoggerListener(),
        ],

        CocoAppConsts::CORE_PROCESS_ON_RESULT_IS_TRUE => [
//            new EchoLoggerListener(),

        ],

        CocoAppConsts::CORE_PROCESS_ON_RESULT_IS_FALSE => [
//            new EchoLoggerListener(),

        ],

    ];