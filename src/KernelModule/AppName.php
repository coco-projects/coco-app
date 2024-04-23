<?php

    namespace Coco\cocoApp\KernelModule;

    trait AppName
    {
        public static function getAppName(): string
        {
            return 'KernelModule';
        }
    }