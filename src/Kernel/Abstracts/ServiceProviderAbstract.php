<?php

    namespace Coco\cocoApp\Kernel\Abstracts;

    use DI\Container;

    abstract class ServiceProviderAbstract
    {
        protected static string $name;

        /**
         * Service register name
         */
        public static function getName(): string
        {
            return static::$name;
        }

        /**
         * Register new service on dependency container
         *
         * @param Container $container
         *
         * @return void
         */
        abstract public function register(Container $container): void;
    }