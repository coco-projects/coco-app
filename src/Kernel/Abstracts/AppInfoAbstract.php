<?php

    namespace Coco\cocoApp\Kernel\Abstracts;

    use Coco\cocoApp\Kernel\Traits\AppBaseTrait;
    use Coco\constants\Consts;

    abstract class AppInfoAbstract
    {
        use AppBaseTrait;

        abstract static public function getAppName(): string;

        abstract public function getAppBasePath(): string;

        public function getControllerDir(): string
        {
            return implode(DIRECTORY_SEPARATOR, [
                $this->getAppBasePath(),
                'Http',
                'Controller',
            ]);
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

        public function getCronsDir(): string
        {
            return implode(DIRECTORY_SEPARATOR, [
                $this->getResourceDir(),
                'crons',
            ]);
        }

        // /var/www/5150/public/static/template/KernelModule/default
        public function getTemplateDir(): string
        {
            return implode(DIRECTORY_SEPARATOR, [
                Consts::get('TEMPLATE_PATH') . static::getAppName(),
                $this->cocoApp->config->base->template,
            ]);
        }

        // /static/template/KernelModule/default/
        public function getTemplateUrl(): string
        {
            return implode(DIRECTORY_SEPARATOR, [
                Consts::get('TEMPLATE_URL') . static::getAppName(),
                $this->cocoApp->config->base->template,
            ]);
        }
    }