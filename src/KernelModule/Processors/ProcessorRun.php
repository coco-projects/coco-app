<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\KernelModule\Processors;

    use Coco\cocoApp\Kernel\Abstracts\ProcessorAbstract;
    use Coco\cocoApp\Kernel\CocoAppConsts;
    use Coco\cocoApp\KernelModule\Events\ProcessorRunEvent;

    class ProcessorRun extends ProcessorAbstract
    {
        protected string $name     = CocoAppConsts::CORE_PROCESS_RUN;
        protected string $msg      = '[ProcessorRun:msg]';
        protected string $debugMsg = '[ProcessorRun:debug-msg]';
        protected bool   $isEnable = true;

        public function exec(): ?bool
        {
            $registry = $this->getRegistry();

            $this->cocoApp->event->triggerEvent(new ProcessorRunEvent($this->cocoApp));

            return true;
        }
    }
