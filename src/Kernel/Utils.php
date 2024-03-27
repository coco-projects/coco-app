<?php

    namespace Coco\cocoApp\Kernel;

    use Nette\Utils\Finder;

    class Utils
    {
        public static function scanDir(string $path, callable $callback): void
        {
            $it = Finder::findFiles('*.php')->in($path);

            foreach ($it as $k => $file)
            {
                call_user_func_array($callback, [$file]);
            }
        }
    }
