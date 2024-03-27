<?php

    namespace Coco\cocoApp\Kernel\Business\ControllerAbstract;

    use \Coco\cocoApp\CocoApp;
    use Coco\cocoApp\Kernel\Business\ControllerWrapper\ConsoleControllerWrapper;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    abstract class ConsoleControllerAbstract extends ControllerAbstract
    {
        public ?ConsoleControllerWrapper $wrapper = null;
        public ?CocoApp                  $cocoApp;
        public ?InputInterface           $input   = null;
        public ?OutputInterface          $output  = null;

        public function __construct(ConsoleControllerWrapper $wrapper)
        {
            $this->wrapper = $wrapper;
        }

        public function init(): void
        {
            $this->cocoApp = $this->wrapper->cocoApp;

            $this->input  = $this->wrapper->input;
            $this->output = $this->wrapper->output;
        }

    }