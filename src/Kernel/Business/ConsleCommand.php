<?php

    namespace Coco\cocoApp\Kernel\Business;

    use Coco\cocoApp\Kernel\Business\ControllerWrapper\ConsoleControllerWrapper;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Input\InputOption;
    use Symfony\Component\Console\Output\ConsoleOutputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class ConsleCommand extends Command
    {
        const route = "route";

        /**
         * @var ConsoleControllerWrapper[] $route
         */
        public array $route = [];

        protected static $defaultName = 'run';

        public function addRoute(string $routeName, ConsoleControllerWrapper $wrapper): static
        {
            $this->route[$routeName] = $wrapper;

            return $this;
        }

        public function __construct(bool $requirePassword = false)
        {
            //初始化操作都写在父类构造器上面
            $this->requirePassword = $requirePassword;

            parent::__construct();
        }

        protected function configure()
        {
            $this->addOption(static::route, 'r', InputOption::VALUE_REQUIRED, '请求的路由', false);

        }

        protected function execute(InputInterface $input, OutputInterface $output): int
        {
            if (!$output instanceof ConsoleOutputInterface)
            {
                throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
            }

            $routeName = $input->getOption(static::route);

            if (!isset($this->route[$routeName]))
            {
                throw new \RuntimeException('未定的路由');
            }

            $wrapper = $this->route[$routeName];

            return $wrapper($input, $output);
        }

    }