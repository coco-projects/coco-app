<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\KernelModule\Listeners;

    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;
    use Coco\cocoApp\Kernel\Abstracts\EventListenerAbstract;
    use Coco\cocoApp\Kernel\CocoApp;
    use Coco\macroable\Macroable;

    class CallableListener extends EventListenerAbstract
    {
        use Macroable;

        /**
         * @var callable $callback
         */
        public        $callback;
        public string $objHash;

        public function __construct( callable $callback, int $priority = 10000)
        {
            $this->callback = $callback;
            $this->priority = $priority;

            parent::__construct(CocoApp::getInstance());

            $this->objHash = '_' . md5(spl_object_hash($this));

            $this::injectMethod($this->objHash, $this->callback);
        }

        public function exec(CoreEventAbstract $coreEventAbstract): void
        {
            $this->{$this->objHash}($coreEventAbstract);
        }
    }
