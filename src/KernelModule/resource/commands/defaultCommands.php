<?php

    use Coco\cocoApp\Kernel\Business\ConsleCommand;
    use Coco\cocoApp\Kernel\Business\ControllerAbstract\ConsoleClosureController;
    use Coco\cocoApp\Kernel\Business\ControllerWrapper\ConsoleControllerWrapper;
    use Coco\cocoApp\Kernel\CocoApp;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Helper\Table;

    return function(ConsleCommand $command) {
        /**
         * @var CocoApp $cocoApp
         */
        $cocoApp = CocoApp::getInstance();
        $appName = \Coco\cocoApp\KernelModule\Info::getAppName();

        //获取注册的命令
        $command->addRoute('/console/list', ConsoleControllerWrapper::closure($appName, function(ConsoleControllerWrapper $ins): int {
            /**
             * @var ConsoleClosureController $this
             */

            $input   = $this->input;
            $output  = $this->output;
            $cocoApp = $this->cocoApp;

            $result = $cocoApp->consleCommand->getRouteList();

            $table = new Table(new Symfony\Component\Console\Output\ConsoleOutput());

//            $table->setStyle('default');
//            $table->setStyle('box');
            $table->setStyle('box-double');
//            $table->setStyle('borderless');
//            $table->setStyle('compact');
//            $table->setStyle('symfony-style-guide');

            $table->setHeaders([
                '路由',
                '描述',
                '注册文件',
                '参数',
            ]);

            $rows = [];

            foreach ($result as $k => $v)
            {
                $rows[] = $v;
                ($k !== count($result) - 1) && $rows[] = new \Symfony\Component\Console\Helper\TableSeparator();
            }

            $table->setRows($rows);
            $table->render();

            return Command::SUCCESS;

        }), __FILE__, '获取注册的任务列表');

    };