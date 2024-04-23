<?php

    namespace Coco\cocoApp\Kernel\Business\ControllerAbstract;

    use Coco\cocoApp\Kernel\Business\ControllerWrapper\ConsoleControllerWrapper;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    abstract class ConsoleControllerAbstract extends ControllerAbstract
    {
        public ?ConsoleControllerWrapper $wrapper = null;
        public ?InputInterface           $input   = null;
        public ?OutputInterface          $output  = null;

        public function __construct(ConsoleControllerWrapper $wrapper)
        {
            $this->wrapper = $wrapper;
            $this->cocoApp = $this->wrapper->cocoApp;
        }

        public function init(): void
        {
            $this->input  = $this->wrapper->input;
            $this->output = $this->wrapper->output;
        }
    }