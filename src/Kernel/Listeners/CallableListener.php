<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\Kernel\Listeners;

    use \Coco\cocoApp\CocoApp;
    use Coco\cocoApp\Kernel\Abstracts\CoreEventAbstract;
    use Coco\cocoApp\Kernel\Abstracts\EventListenerAbstract;
    use Coco\macroable\Macroable;

    class CallableListener extends EventListenerAbstract
    {
        use Macroable;

        /**
         * @var callable $callback
         */
        public        $callback;
        public string $objHash;

        public function __construct(CocoApp $app, callable $callback, int $priority = 10000)
        {
            $this->callback = $callback;
            $this->priority = $priority;

            parent::__construct($app);

            $this->objHash = '_' . md5(spl_object_hash($this));

            $this::injectMethod($this->objHash, $this->callback);
        }

        public function exec(CoreEventAbstract $coreEventAbstract): void
        {
            $this->{$this->objHash}($coreEventAbstract);
        }
    }
