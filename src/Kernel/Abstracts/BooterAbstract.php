<?php

    namespace Coco\cocoApp\Kernel\Abstracts;

    use Coco\cocoApp\Kernel\Traits\AppBaseTrait;

    abstract class BooterAbstract
    {
        use AppBaseTrait;

        protected AppInfoAbstract $appInfo;
        protected ?string         $userConfigDir = null;

        public function getAppInfo(): AppInfoAbstract
        {
            return $this->appInfo;
        }

        public function setUserConfigDir(string $userConfigDir): static
        {
            $this->userConfigDir = $userConfigDir;

            return $this;
        }

        public function getUserConfigDir(): ?string
        {
            return $this->userConfigDir;
        }
    }