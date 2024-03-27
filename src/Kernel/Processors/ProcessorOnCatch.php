<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\Kernel\Processors;

    use Coco\cocoApp\CocoAppConsts;
    use Coco\cocoApp\Kernel\Abstracts\ProcessorAbstract;
    use Coco\cocoApp\Kernel\CoreEvents\ProcessorOnCatchEvent;

    class ProcessorOnCatch extends ProcessorAbstract
    {
        protected string $name     = CocoAppConsts::CORE_PROCESS_ON_CATCH;
        protected string $msg      = '';
        protected string $debugMsg = '';
        protected bool   $isEnable = true;

        public function exec(): ?bool
        {
            $registry = $this->getRegistry();

            $this->cocoApp->event->triggerEvent(new ProcessorOnCatchEvent($this->cocoApp));

            return true;
        }
    }
