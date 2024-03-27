<?php

    declare(strict_types = 1);

    namespace Coco\cocoApp\Kernel\Processors;

    use Coco\cocoApp\CocoAppConsts;
    use Coco\cocoApp\Kernel\Abstracts\ProcessorAbstract;
    use Coco\cocoApp\Kernel\CoreEvents\ProcessorOnResultIsTrueEvent;

    class ProcessorOnResultIsTrue extends ProcessorAbstract
    {
        protected string $name     = CocoAppConsts::CORE_PROCESS_ON_RESULT_IS_TRUE;
        protected string $msg      = '';
        protected string $debugMsg = '';
        protected bool   $isEnable = true;

        public function exec(): ?bool
        {
            $registry = $this->getRegistry();

            $this->cocoApp->event->triggerEvent(new ProcessorOnResultIsTrueEvent($this->cocoApp));

            return true;
        }
    }
