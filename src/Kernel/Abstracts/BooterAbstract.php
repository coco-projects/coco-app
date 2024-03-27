<?php

    namespace Coco\cocoApp\Kernel\Abstracts;

    abstract class BooterAbstract
    {
        protected AppInfoAbstract $appInfo;

        public function getAppInfo(): AppInfoAbstract
        {
            return $this->appInfo;
        }
    }