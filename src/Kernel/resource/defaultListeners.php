<?php

    use Coco\cocoApp\CocoApp;
    use Coco\cocoApp\CocoAppConsts;
    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;
    use Coco\cocoApp\Kernel\Listeners\CallableListener;
    use Coco\cocoApp\Kernel\Listeners\SystemInitListener;
    use Coco\cocoApp\Kernel\Listeners\EchoExceptionMsgListener;
    use Coco\cocoApp\Kernel\Listeners\ThrowExceptionMsgListener;

    $app = CocoApp::getInstance();

    return [

        CocoAppConsts::CORE_SYSTEM_INIT_START => [
            new CallableListener($app, function(CoreEventAbstract $coreEventAbstract) {

                /**
                 * @var CallableListener $this
                 */

                $this->cocoApp->timer->start();

            }),
        ],

        CocoAppConsts::CORE_RUN_LOGIC_START => [

        ],

        CocoAppConsts::CORE_PROCESS_ON_START => [
            new SystemInitListener($app),
        ],

        CocoAppConsts::CORE_PROCESS_RUN_BEFORE => [

        ],

        CocoAppConsts::CORE_PROCESS_RUN => [

        ],

        CocoAppConsts::CORE_PROCESS_RUN_AFTER => [

        ],

        CocoAppConsts::CORE_PROCESS_ON_DONE => [

        ],

        CocoAppConsts::CORE_PROCESS_ON_CATCH => [
//            new EchoExceptionMsgListener($app),
//            new ThrowExceptionMsgListener($app),
//            new EchoLoggerListener($app),
        ],

        CocoAppConsts::CORE_PROCESS_ON_RESULT_IS_TRUE => [

        ],

        CocoAppConsts::CORE_PROCESS_ON_RESULT_IS_FALSE => [

        ],

        CocoAppConsts::CORE_RUN_LOGIC_DONE => [
            new CallableListener($app, function(CoreEventAbstract $coreEventAbstract) {

                /**
                 * @var CallableListener $this
                 */
                $this->cocoApp->timer->mark(CocoAppConsts::CORE_RUN_LOGIC_DONE);
            }),
        ],
    ];