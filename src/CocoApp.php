<?php

    namespace Coco\cocoApp;

    use Coco\config\Config;
    use Coco\config\Utils;
    use Coco\constants\Consts;
    use Coco\env\EnvParser;
    use Coco\cocoApp\Kernel\Abstracts\BooterAbstract;
    use Coco\cocoApp\Kernel\Abstracts\EventListenerAbstract;
    use Coco\cocoApp\Kernel\Abstracts\ServerAbstract;
    use Coco\cocoApp\Kernel\Abstracts\ServiceProviderAbstract;
    use Coco\cocoApp\Kernel\AppInfoRegistry;
    use Coco\cocoApp\Kernel\Business\ConsleCommand;
    use Coco\cocoApp\Kernel\CoreEvents\ExecLogicDoneEvent;
    use Coco\cocoApp\Kernel\CoreEvents\ExecLogicStartEvent;
    use Coco\cocoApp\Kernel\CoreEvents\SystemInitStartEvent;
    use Coco\cocoApp\Kernel\Exceptions\KernelException;
    use Coco\cocoApp\Kernel\Exceptions\ServiceException;
    use Coco\cocoApp\Kernel\Processors\ProcessorOnCatch;
    use Coco\cocoApp\Kernel\Processors\ProcessorOnDone;
    use Coco\cocoApp\Kernel\Processors\ProcessorOnResultIsFalse;
    use Coco\cocoApp\Kernel\Processors\ProcessorOnResultIsTrue;
    use Coco\cocoApp\Kernel\Processors\ProcessorOnStart;
    use Coco\cocoApp\Kernel\Processors\ProcessorRun;
    use Coco\cocoApp\Kernel\Services\AppInfoRegistryProvider;
    use Coco\cocoApp\Kernel\Services\CacheProvider;
    use Coco\cocoApp\Kernel\Services\CocoAppProvider;
    use Coco\cocoApp\Kernel\Services\ConsleCommandProvider;
    use Coco\cocoApp\Kernel\Services\ConsoleAppProvider;
    use Coco\cocoApp\Kernel\Services\EventManagerProvider;
    use Coco\cocoApp\Kernel\Services\Logger\Hander\ErrorLogHandlerProvider;
    use Coco\cocoApp\Kernel\Services\Logger\Hander\RedisHandlerProvider;
    use Coco\cocoApp\Kernel\Services\Logger\Hander\RotatingFileHandlerProvider;
    use Coco\cocoApp\Kernel\Services\Logger\Hander\SocketHandlerProvider;
    use Coco\cocoApp\Kernel\Services\Logger\Hander\StreamHandlerProvider;
    use Coco\cocoApp\Kernel\Services\Logger\LineFormatterProvider;
    use Coco\cocoApp\Kernel\Services\LoggerProvider;
    use Coco\cocoApp\Kernel\Services\PredisProvider;
    use Coco\cocoApp\Kernel\Services\ProcessRegistryProvider;
    use Coco\cocoApp\Kernel\Services\RedisProvider;
    use Coco\cocoApp\Kernel\Services\RequestProvider;
    use Coco\cocoApp\Kernel\Services\SlimAppProvider;
    use Coco\cocoApp\Kernel\Services\TimerProvider;
    use Coco\processManager\ProcessRegistry;
    use Coco\timer\Timer;
    use DI\Container;
    use Laminas\EventManager\EventManager;
    use Monolog\Logger;
    use Nette\Utils\Finder;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Slim\App;
    use Slim\Interfaces\RouteInterface;
    use Slim\Routing\RouteContext;
    use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;
    use Symfony\Component\Console\Application;

    /**
     * @property ConsleCommand        $consleCommand
     * @property \Predis\Client       $predis
     * @property Logger               $logger
     * @property Timer                $timer
     * @property \Redis               $redis
     * @property RedisTagAwareAdapter $cache
     * @property Config               $config
     * @property EventManager         $event
     * @property ProcessRegistry      $process
     * @property App                  $slim
     * @property AppInfoRegistry      $appInfoRegistry
     * @property CocoApp              $cocoApp
     * @property Application          $console
     * @property Request              $request
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
        protected array        $booters     = [];
        protected array        $userConfigs = [];
        protected string       $publicPath  = '.';
        protected ?string      $appPath     = null;
        public Response        $response;
        public ?RouteContext   $routeContext;
        public ?RouteInterface $route;

        protected function __construct(string $publicPath = '.', $appPath = null)
        {
            if (!is_dir(realpath($publicPath)))
            {
                throw new KernelException('[' . $publicPath . '] folder does not exist');
            }

            if (is_null($appPath))
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

            Consts::init();

            Consts::set('PUBLIC_PATH', realpath(rtrim($this->publicPath, '/\\')) . DIRECTORY_SEPARATOR);
            Consts::set('APP_PATH', $this->appPath);
            Consts::set('ROOT_PATH', '<PUBLIC_PATH>../');
            Consts::set('ENV_PATH', '<ROOT_PATH>envs/');
            Consts::set('RUNTIME_PATH', '<ROOT_PATH>runtime/');
            Consts::set('CACHE_PATH', '<RUNTIME_PATH>cache/');
            Consts::set('TEMP_PATH', '<RUNTIME_PATH>temp/');
            Consts::set('LOG_PATH', '<RUNTIME_PATH>log/');
            Consts::set('CORE_BAES_PATH', __DIR__ . '/');
            Consts::set('CORE_RESOURCE_PATH', '<CORE_BAES_PATH>/Kernel/resource/');

            $this->registerServices([
                ProcessRegistryProvider::class,
                EventManagerProvider::class,
                RequestProvider::class,
                SlimAppProvider::class,
                AppInfoRegistryProvider::class,
                CocoAppProvider::class,
                ConsoleAppProvider::class,
                ConsleCommandProvider::class,
                TimerProvider::class,
                RedisProvider::class,
                PredisProvider::class,
                CacheProvider::class,
                LoggerProvider::class,
                //--------------------------//
                RotatingFileHandlerProvider::class,
                LineFormatterProvider::class,
                StreamHandlerProvider::class,
                SocketHandlerProvider::class,
                ErrorLogHandlerProvider::class,
                RedisHandlerProvider::class,
            ]);

            $this->registerEventListeners(require Consts::getValue('CORE_RESOURCE_PATH') . 'defaultListeners.php');

            $this->event->triggerEvent(new SystemInitStartEvent($this));

            $this->registerConfig(require Consts::getValue('CORE_RESOURCE_PATH') . 'defaultConfig.php');

            $this->initUserSettings();
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

        public function afterInitBooter(): static
        {
            $this->setAppDebug(!!$this->config->base->app_debug);
            $this->initProcess();

            return $this;
        }


        public function listen(): void
        {
            $this->event->triggerEvent(new ExecLogicStartEvent($this));
            $this->process->executeLogics();
            $this->event->triggerEvent(new ExecLogicDoneEvent($this));
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

        public function isAppDebug(): bool
        {
            return $this->appDebug;
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

        public function getAllAppConfigs(): array
        {
            return $this->allAppConfigs;
        }


        protected function setAppDebug(bool $appDebug): static
        {
            $this->appDebug = $appDebug;
            $this->process->setIsDebug($appDebug);

            return $this;
        }

        private function initProcess(): void
        {
            $this->process->setOnStart(new ProcessorOnStart($this));
            $this->process->setOnDone(new ProcessorOnDone($this));
            $this->process->setOnCatch(new ProcessorOnCatch($this));
            $this->process->setOnResultIsTrue(new ProcessorOnResultIsTrue($this));
            $this->process->setOnResultIsFalse(new ProcessorOnResultIsFalse($this));
            $this->process->apendLogic(new ProcessorRun($this));
        }

        protected function initUserSettings(): static
        {
            $it = Finder::findDirectories('*')->in(Consts::getValue('APP_PATH'));

            $loadedBooters = [];

            foreach ($it as $k => $dir)
            {
                $booterFile = $dir . DIRECTORY_SEPARATOR . 'booter.php';
                $envFile    = $dir . DIRECTORY_SEPARATOR . '.env';
                $configDir  = $dir . DIRECTORY_SEPARATOR . 'configs';

                if (!is_file($booterFile))
                {
                    continue;
                }

                $booterClass = require $booterFile;

                if ((!class_exists($booterClass)) or isset($loadedBooters[$booterClass]))
                {
                    continue;
                }

                /**
                 * @var $booterObject BooterAbstract
                 */
                $booterObject = new $booterClass();

                $appName = $booterObject->getAppInfo()->getAppName();

                $this->booters[$appName] = $booterObject;

                if (is_file($envFile))
                {
                    EnvParser::loadEnvFile($envFile);
                }

                if (is_dir($configDir))
                {
                    $this->userConfigs[] = $configDir;
                }

                $loadedBooters[$booterClass] = 1;
            }

            return $this;
        }

        public function makeSkeleton()
        {

        }
    }
