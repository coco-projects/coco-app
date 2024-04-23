<?php

    namespace Coco\cocoApp\KernelModule\Services\Logger;

    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Coco\cocoApp\Kernel\CocoApp;
    use DI\Container;
    use Monolog\Formatter\LineFormatter;

    class LineFormatterProvider extends ServiceProviderAbstract
    {
        public static string $name = LineFormatter::class;

        public function register(Container $container): void
        {

            $container->set(static::$name, function(Container $container) {

                /**
                 * @var CocoApp $app
                 */
                $app = $container->get('cocoApp');

                return new LineFormatter(
                    $app->config->log->LineFormatter->format,
                    $app->config->log->LineFormatter->dateFormat,
                    $app->config->log->LineFormatter->allowInlineLineBreaks,
                    $app->config->log->LineFormatter->ignoreEmptyContextAndExtra,
                    $app->config->log->LineFormatter->includeStacktraces);
            });
        }
    }