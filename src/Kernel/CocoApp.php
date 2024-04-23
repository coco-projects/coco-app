<?php

    namespace Coco\cocoApp\Kernel;

    use Coco\cocoApp\Kernel\Abstracts\BooterAbstract;
    use Coco\cocoApp\Kernel\Abstracts\EventListenerAbstract;
    use Coco\cocoApp\Kernel\Abstracts\ServerAbstract;
    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Coco\cocoApp\Kernel\Business\ConsleCommand;
    use Coco\cocoApp\KernelModule\Booter;
    use Coco\cocoApp\KernelModule\Exceptions\KernelException;
    use Coco\cocoApp\KernelModule\Exceptions\ServiceException;
    use Coco\cocoApp\KernelModule\Processors\ProcessorOnCatch;
    use Coco\cocoApp\KernelModule\Processors\ProcessorOnDone;
    use Coco\cocoApp\KernelModule\Processors\ProcessorOnResultIsFalse;
    use Coco\cocoApp\KernelModule\Processors\ProcessorOnResultIsTrue;
    use Coco\cocoApp\KernelModule\Processors\ProcessorOnStart;
    use Coco\cocoApp\KernelModule\Processors\ProcessorRun;
    use Coco\cocoApp\KernelModule\Services\CacheProvider;
    use Coco\cocoApp\KernelModule\Services\CocoAppProvider;
    use Coco\cocoApp\KernelModule\Services\ConsleCommandProvider;
    use Coco\cocoApp\KernelModule\Services\ConsoleAppProvider;
    use Coco\cocoApp\KernelModule\Services\CronScheduleProvider;
    use Coco\cocoApp\KernelModule\Services\EnvDetectorProvider;
    use Coco\cocoApp\KernelModule\Services\EventManagerProvider;
    use Coco\cocoApp\KernelModule\Services\Logger\Hander\ErrorLogHandlerProvider;
    use Coco\cocoApp\KernelModule\Services\Logger\Hander\RedisHandlerProvider;
    use Coco\cocoApp\KernelModule\Services\Logger\Hander\RotatingFileHandlerProvider;
    use Coco\cocoApp\KernelModule\Services\Logger\Hander\SocketHandlerProvider;
    use Coco\cocoApp\KernelModule\Services\Logger\Hander\StreamHandlerProvider;
    use Coco\cocoApp\KernelModule\Services\Logger\LineFormatterProvider;
    use Coco\cocoApp\KernelModule\Services\LoggerProvider;
    use Coco\cocoApp\KernelModule\Services\PredisProvider;
    use Coco\cocoApp\KernelModule\Services\ProcessRegistryProvider;
    use Coco\cocoApp\KernelModule\Services\RedisProvider;
    use Coco\cocoApp\KernelModule\Services\RequestProvider;
    use Coco\cocoApp\KernelModule\Services\SlimAppProvider;
    use Coco\cocoApp\KernelModule\Services\TimerProvider;
    use Coco\cocoApp\KernelModule\Services\ViewProvider;
    use Coco\config\Config;
    use Coco\config\Utils;
    use Coco\constants\Consts;
    use Coco\env\EnvParser;
    use Coco\envDetector\Factory;
    use Coco\processManager\ProcessRegistry;
    use Coco\timer\Timer;
    use DI\Container;
    use Laminas\EventManager\EventManager;
    use Monolog\Logger;
    use Nette\Utils\Finder;
    use Predis\Client;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Slim\App;
    use Slim\Csrf\Guard;
    use Slim\Flash\Messages;
    use Slim\Interfaces\RouteInterface;
    use Slim\Routing\RouteContext;
    use Slim\Views\Twig;
    use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;
    use Symfony\Component\Console\Application;
    use Coco\cron\Schedule;
    use Coco\cocoApp\KernelModule\Services\CsrfProvider;
    use Coco\cocoApp\KernelModule\Services\FlashMessageProvider;

    /**
     * @property ConsleCommand        $consleCommand
     * @property Client               $predis
     * @property Logger               $logger
     * @property Timer                $timer
     * @property \Redis               $redis
     * @property RedisTagAwareAdapter $cache
     * @property Config               $config
     * @property EventManager         $event
     * @property ProcessRegistry      $process
     * @property App                  $slim
     * @property CocoApp              $cocoApp
     * @property Application          $console
     * @property Request              $request
     * @property Twig                 $view
     * @property Schedule             $cron
     * @property Factory              $envDetector
     * @property Messages             $flash
     * @property Guard                $crsf
     */
    class CocoApp
    {
        protected static ?CocoApp $instance = null;

        protected ?Container      $container          = null;
        protected ?ServerAbstract $server             = null;
        protected array           $definitions        = [];
        protected bool            $appDebug           = false;
        protected array           $registeredServices = [];
        protected array           $allAppConfigs      = [];

        /**
         * @var BooterAbstract[] $booters
         */
        protected array        $booters        = [];
        protected array        $userConfigs    = [];
        protected string       $publicPath     = '.';
        protected ?string      $appPath        = null;
        public Response        $response;
        public ?RouteContext   $routeContext;
        public ?RouteInterface $route;
        protected ?string      $currentAppName = null;

        protected function __construct(string $publicPath = '.', $appPath = null)
        {
            if (!is_dir(realpath($publicPath)))
            {
                throw new KernelException('[' . $publicPath . '] folder does not exist');
            }

            if (!is_string($appPath))
            {
                $appPath = dirname($publicPath) . DIRECTORY_SEPARATOR . 'apps';
            }

            if (!is_dir(realpath($appPath)))
            {
                throw new KernelException('[' . $appPath . '] folder does not exist');
            }

            $this->publicPath = $publicPath;
            $this->appPath    = $appPath;

            static::$instance = $this;
            $this->container  = new Container();

            $this->initConsts();
            $this->initEnv();
            $this->initUserSettings();
            $this->iniServices();
            $this->iniProcess();
        }

        public static function init(string $publicPath = '.', $appPath = null): static
        {
            if (is_null(static::$instance))
            {
                static::$instance = new static($publicPath, $appPath);
            }

            return static::$instance;
        }

        public static function getInstance(): ?static
        {
            return static::$instance;
        }

        public function getUserConfigs(): array
        {
            return $this->userConfigs;
        }

        public function __get(string $name)
        {
            if ($this->hasService($name))
            {
                return $this->getContainer()->get($name);
            }

            throw new ServiceException('service [' . $name . '] not funod');
        }


        public function getBooters(): array
        {
            return $this->booters;
        }

        public function getBooterByAppName(string $appName): BooterAbstract
        {
            if (isset($this->booters[$appName]))
            {
                return $this->booters[$appName];
            }
            throw new \RuntimeException('未定义的应用名：' . $appName);
        }


        public function getContainer(): ?Container
        {
            return $this->container;
        }

        public function getRegisteredServices(): array
        {
            return $this->registeredServices;
        }


        public function hasService(string $name): bool
        {
            return $this->getContainer()->has($name);
        }

        public function getServer(): ?ServerAbstract
        {
            return $this->server;
        }

        public function setServer(?ServerAbstract $server): static
        {
            $this->server = $server;

            return $this;
        }

        public function getAllAppConfigs(): array
        {
            return $this->allAppConfigs;
        }

        public function getCurrentAppName(): ?string
        {
            return $this->currentAppName;
        }

        public function isAppDebug(): bool
        {
            return $this->appDebug;
        }

        public function setAppDebug(bool $appDebug): static
        {
            $this->appDebug = $appDebug;
            $this->process->setIsDebug($appDebug);

            return $this;
        }


        /**------------------------------------------------------------------------------*/
        /**------------------------------------------------------------------------------*/

        public function listen(): void
        {
            $this->process->executeLogics();
        }

        /**------------------------------------------------------------------------------*/
        /**------------------------------------------------------------------------------*/

        private function initConsts(): static
        {
            Consts::init();
            Consts::set('PUBLIC_PATH', realpath(rtrim($this->publicPath, '/\\')) . DIRECTORY_SEPARATOR);
            Consts::set('APP_PATH', $this->appPath);

            Consts::set('ROOT_PATH', '<PUBLIC_PATH>../');
            Consts::set('STATIC_PATH', '<PUBLIC_PATH>static/');
            Consts::set('TEMPLATE_PATH', '<STATIC_PATH>template/');

            Consts::set('ENV_PATH', '<ROOT_PATH>envs/');
            Consts::set('RUNTIME_PATH', '<ROOT_PATH>runtime/');
            Consts::set('CACHE_PATH', '<RUNTIME_PATH>cache/');
            Consts::set('TEMP_PATH', '<RUNTIME_PATH>temp/');
            Consts::set('LOG_PATH', '<RUNTIME_PATH>log/');

            Consts::set('CORE_BAES_PATH', __DIR__ . '/');
            Consts::set('CORE_RESOURCE_PATH', '<CORE_BAES_PATH>Kernel/resource/');

            Consts::set('ROOT_URL', '/');
            Consts::set('STATIC_URL', '<ROOT_URL>static/');
            Consts::set('TEMPLATE_URL', '<STATIC_URL>template/');

            return $this;
        }

        private function initEnv(): static
        {
            $envFile = Consts::get('ROOT_PATH') . DIRECTORY_SEPARATOR . '.env';

            if (is_file($envFile))
            {
                EnvParser::loadEnvFile($envFile);
            }

            return $this;
        }

        private function initUserSettings(): static
        {
            $this->booters['KernelModule'] = new Booter($this);

            $it = Finder::findDirectories('*')->in(Consts::getValue('APP_PATH'));

            $loadedBooters = [];

            foreach ($it as $k => $dir)
            {
                $booterFile = $dir . DIRECTORY_SEPARATOR . 'booter.php';
                $configDir  = $dir . DIRECTORY_SEPARATOR . 'configs';

                if (!is_file($booterFile))
                {
                    continue;
                }

                $booterClass = require $booterFile;

                if (!class_exists($booterClass) or isset($loadedBooters[$booterClass]))
                {
                    continue;
                }

                /**
                 * @var $booterObject BooterAbstract
                 */
                $booterObject = new $booterClass($this);

                if (!($booterObject instanceof BooterAbstract))
                {
                    continue;
                }

                $appName = $booterObject->getAppInfo()::getAppName();

                if (is_dir($configDir))
                {
                    $booterObject->setUserConfigDir($configDir);
                }

                $this->booters[$appName] = $booterObject;

                $loadedBooters[$booterClass] = 1;
            }

            return $this;
        }

        private function iniServices(): static
        {
            $this->registerServices([
                ProcessRegistryProvider::class,
                EventManagerProvider::class,
                RequestProvider::class,
                SlimAppProvider::class,
                CocoAppProvider::class,
                ConsoleAppProvider::class,
                ConsleCommandProvider::class,
                TimerProvider::class,
                RedisProvider::class,
                PredisProvider::class,
                CacheProvider::class,
                LoggerProvider::class,
                ViewProvider::class,
                CronScheduleProvider::class,
                EnvDetectorProvider::class,
                //--------------------------//
                RotatingFileHandlerProvider::class,
                LineFormatterProvider::class,
                StreamHandlerProvider::class,
                SocketHandlerProvider::class,
                ErrorLogHandlerProvider::class,
                RedisHandlerProvider::class,

                FlashMessageProvider::class,
                CsrfProvider::class,
            ]);

            return $this;
        }

        private function iniProcess(): static
        {
            $this->process->setOnStart(new ProcessorOnStart($this));
            $this->process->setOnDone(new ProcessorOnDone($this));
            $this->process->setOnCatch(new ProcessorOnCatch($this));
            $this->process->setOnResultIsTrue(new ProcessorOnResultIsTrue($this));
            $this->process->setOnResultIsFalse(new ProcessorOnResultIsFalse($this));
            $this->process->apendLogic(new ProcessorRun($this));

            return $this;
        }

        /**------------------------------------------------------------------------------*/
        /**------------------------------------------------------------------------------*/

        public function registerConfigsBydir($path): static
        {
            \Coco\cocoApp\Kernel\Utils::scanDir($path, function($file) {
                $configFile = require $file;

                if (is_array($configFile))
                {
                    $this->registerConfig($configFile);
                }
            });

            return $this;
        }

        public function registerConfig($config = []): static
        {
            $this->allAppConfigs = Utils::arrayMerge($this->allAppConfigs, $config);

            return $this;
        }

        public function registerServicesByDir($path): static
        {
            \Coco\cocoApp\Kernel\Utils::scanDir($path, function($file) {
                $services = require $file;

                if (is_array($services))
                {
                    $this->registerServices($services);
                }
            });

            return $this;
        }

        public function registerServices(array $services): static
        {
            foreach ($services as $service)
            {
                $this->registerService($service);
            }

            return $this;
        }

        public function registerService(string $service): static
        {
            $serviceObj = new $service();

            if (!$serviceObj instanceof ServiceProviderAbstract)
            {
                throw new KernelException('[' . $service . '] must implements Coco\Kernel\Abstracts\ServiceProviderAbstract');
            }

            $serviceObj->register($this->getContainer());

            $this->registeredServices[$serviceObj::getName()] = $serviceObj::class;

            return $this;
        }

        public function registerEventListenersByDir($path): static
        {
            \Coco\cocoApp\Kernel\Utils::scanDir($path, function($file) {
                $array = require $file;

                if (is_array($array))
                {
                    $this->registerEventListeners($array);
                }
            });

            return $this;
        }

        public function registerEventListeners(array $eventListeners): static
        {
            foreach ($eventListeners as $eventName => $eventListener)
            {
                foreach ($eventListener as $listener)
                {
                    $this->registerEventListener($eventName, $listener);
                }
            }

            return $this;
        }

        public function registerEventListener(string $eventName, EventListenerAbstract $eventListener): static
        {
            $this->event->attach($eventName, $eventListener->getCallable(), $eventListener->getPriority());

            return $this;
        }


        public function registerRoutersBydir($path): static
        {
            \Coco\cocoApp\Kernel\Utils::scanDir($path, function($file) {
                $callback = require $file;

                if (is_callable($callback))
                {
                    $this->registerRouter($callback);
                }
            });

            return $this;
        }

        public function registerRouter(callable $callback): static
        {
            call_user_func_array($callback, [$this->cocoApp->slim]);

            return $this;
        }


        public function registerMiddlewareBydir($path): static
        {
            \Coco\cocoApp\Kernel\Utils::scanDir($path, function($file) {
                $callback = require $file;

                if (is_callable($callback))
                {
                    $this->registerMiddleware($callback);
                }
            });

            return $this;
        }

        public function registerMiddleware(callable $callback): static
        {
            call_user_func_array($callback, [$this->cocoApp->slim]);

            return $this;
        }

        public function registerCommandsBydir($path): static
        {
            \Coco\cocoApp\Kernel\Utils::scanDir($path, function($file) {
                $callback = require $file;

                if (is_callable($callback))
                {
                    $this->registerCommand($callback);
                }
            });

            return $this;
        }

        public function registerCommand(callable $callback): static
        {
            call_user_func_array($callback, [$this->cocoApp->consleCommand]);

            return $this;
        }

        public function registerCronBydir($path): static
        {
            \Coco\cocoApp\Kernel\Utils::scanDir($path, function($file) {
                $callback = require $file;

                if (is_callable($callback))
                {
                    $this->registerCron($callback);
                }
            });

            return $this;
        }

        public function registerCron(callable $callback): static
        {
            call_user_func_array($callback, [$this->cocoApp->cron]);

            return $this;
        }

        /**------------------------------------------------------------------------------*/
        /**------------------------------------------------------------------------------*/
        public function makeSkeleton()
        {

        }
    }
