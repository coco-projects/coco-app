<?php

    namespace Coco\cocoApp\KernelModule\Console\Controller;

    use Coco\cocoApp\Kernel\Business\ControllerAbstract\ConsoleControllerAbstract;

    class BaseController extends ConsoleControllerAbstract
    {
        use \Coco\cocoApp\KernelModule\AppName;
    }