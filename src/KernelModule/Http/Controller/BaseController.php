<?php

    namespace Coco\cocoApp\KernelModule\Http\Controller;

    use Coco\cocoApp\Kernel\Business\ControllerAbstract\WebControllerAbstract;

    class BaseController extends WebControllerAbstract
    {
        use \Coco\cocoApp\KernelModule\AppName;
    }