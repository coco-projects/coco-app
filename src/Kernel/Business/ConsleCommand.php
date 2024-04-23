<?php

    namespace Coco\cocoApp\Kernel\Business;

    use Coco\cocoApp\Kernel\Business\ControllerWrapper\ConsoleControllerWrapper;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Input\InputOption;
    use Symfony\Component\Console\Output\ConsoleOutputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class ConsleCommand extends Command
    {
        const ROUTE          = "route";
        const PARAMS         = "params";
        const PARAMS_TYPE    = "paramsType";
        const TYPE_JSON      = "json";
        const TYPE_QUERY     = "query";
        const TYPE_JSON_FILE = "json_file";

        /**
         * @var ConsoleControllerWrapper[] $route
         */
        public array $route = [];

        protected static $defaultName = 'run';

        public function addRoute(string $routeName, callable $wrapper, string $file, string $description = '', $params = []): static
        {
            $this->route[$routeName] = [
                "wrapper"     => $wrapper,
                "description" => $description,
                "file"        => $file,
                "params"      => $params,
            ];

            return $this;
        }

        public function getRouteList(): array
        {
            $result = [];
            foreach ($this->route as $routeName => $v)
            {
                $t = [];
                foreach ($v['params'] as $field => $value)
                {
                    $t[] = "$field" . ($value['require'] ? '[*]' : '[-]') . ':' . $value['description'];
                }

                $result[] = [
                    "route"       => $routeName,
                    "description" => $v['description'],
                    "file"        => $v['file'],
                    "params"      => implode(PHP_EOL, $t),
                ];
            }

            return $result;
        }

        public function __construct()
        {
            //初始化操作都写在父类构造器上面
            parent::__construct();
        }

        protected function configure(): void
        {
            $this
//                ->setName('')
//                ->setDescription('')
//                ->setHelp('This command makes a task file skeleton.')
                ->setDefinition([
                    new InputArgument(static::ROUTE, InputArgument::REQUIRED, '请求的路由'),
                    new InputOption(static::PARAMS, 'p', InputOption::VALUE_REQUIRED, '请求的路由', false),
                    new InputOption(static::PARAMS_TYPE, 't', InputOption::VALUE_REQUIRED, '请求的路由参数类型', static::TYPE_QUERY),
                ]);
        }

        //  php console.php run /cron/runById -t json --params=\{\"id\":\"1\",\"b\":\"2\"\}
        //  php console.php run /cron/runById -t query --params="id=1&b=2"
        //  php console.php run /cron/runById -t json_file --params=./public/data/params.json
        protected function execute(InputInterface $input, OutputInterface $output): int
        {
            if (!$output instanceof ConsoleOutputInterface)
            {
                throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
            }

            $routeName = $input->getArgument(static::ROUTE);

            if (!isset($this->route[$routeName]))
            {
                throw new \RuntimeException('未定的路由');
            }

            /**
             * @var callable $wrapper
             */
            $wrapper = $this->route[$routeName]["wrapper"];

            return call_user_func_array($wrapper, [
                $input,
                $output,
            ]);
        }

    }