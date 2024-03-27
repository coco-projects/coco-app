<?php

    namespace Coco\cocoApp\Kernel\Abstracts;

    use Coco\cocoApp\Kernel\Traits\AppBaseTrait;
    use Coco\processManager\LogicAbstract;

    abstract class ProcessorAbstract extends LogicAbstract
    {
        use AppBaseTrait;
    }