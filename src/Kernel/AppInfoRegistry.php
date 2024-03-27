<?php

    namespace Coco\cocoApp\Kernel;

    use Coco\cocoApp\Kernel\Abstracts\AppInfoAbstract;

    class AppInfoRegistry
    {
        /**
         * @var AppInfoAbstract[]
         */
        public array $appInfos = [];

        public function addApp(AppInfoAbstract $appInfo): static
        {
            $this->appInfos[$appInfo->getAppName()] = $appInfo;

            return $this;
        }

    }