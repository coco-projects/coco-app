<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\KernelModule\Processors;

    use Coco\cocoApp\Kernel\Abstracts\ProcessorAbstract;
    use Coco\cocoApp\Kernel\CocoAppConsts;
    use Coco\cocoApp\KernelModule\Events\ProcessorOnResultIsFalseEvent;

    class ProcessorOnResultIsFalse extends ProcessorAbstract
    {
        protected string $name     = CocoAppConsts::CORE_PROCESS_ON_RESULT_IS_FALSE;
        protected string $msg      = '';
        protected string $debugMsg = '';
        protected bool   $isEnable = true;

        public function exec(): ?bool
        {
            $registry = $this->getRegistry();

            $this->cocoApp->event->triggerEvent(new ProcessorOnResultIsFalseEvent($this->cocoApp));

            return true;
        }
    }
