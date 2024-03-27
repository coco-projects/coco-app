<?php

    namespace Coco\cocoApp\Kernel\Abstracts;

    abstract class AppInfoAbstract
    {
        protected string $appName;

        abstract public function getAppBasePath(): string;


        public function getAppName(): string
        {
            return $this->appName;
        }

        public function getResourceDir(): string
        {
            return implode(DIRECTORY_SEPARATOR, [
                $this->getAppBasePath(),
                'resource',
            ]);
        }

        public function getConfigDir(): string
        {
            return implode(DIRECTORY_SEPARATOR, [
                $this->getResourceDir(),
                'configs',
            ]);
        }

        public function getMiddlewareDir(): string
        {
            return implode(DIRECTORY_SEPARATOR, [
                $this->getResourceDir(),
                'middleware',
            ]);
        }

        public function getRoutesDir(): string
        {
            return implode(DIRECTORY_SEPARATOR, [
                $this->getResourceDir(),
                'routes',
            ]);
        }

        public function getCommandsDir(): string
        {
            return implode(DIRECTORY_SEPARATOR, [
                $this->getResourceDir(),
                'commands',
            ]);
        }

        public function getServicesDir(): string
        {
            return implode(DIRECTORY_SEPARATOR, [
                $this->getResourceDir(),
                'services',
            ]);
        }

        public function getListenersDir(): string
        {
            return implode(DIRECTORY_SEPARATOR, [
                $this->getResourceDir(),
                'listeners',
            ]);
        }
    }