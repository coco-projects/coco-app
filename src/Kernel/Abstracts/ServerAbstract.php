<?php

    namespace Coco\cocoApp\Kernel\Abstracts;

    use \Coco\cocoApp\CocoApp;

    abstract class ServerAbstract
    {
        public CocoApp $cocoApp;

        public function __construct(CocoApp $cocoApp)
        {
            $this->cocoApp = $cocoApp;
            $cocoApp->setServer($this);
            $this->initRunnerListener();
            $this->boot();
        }

        public function listen(): void
        {
            $this->cocoApp->listen();
        }

        abstract protected function boot(): void;

        abstract protected function initRunnerListener(): static;

        abstract protected function initBooters(array $booters): static;

    }