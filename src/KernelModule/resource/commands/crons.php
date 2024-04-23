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

        //获取注册的任务列表
        $command->addRoute('/cron/list', ConsoleControllerWrapper::closure($appName, function(ConsoleControllerWrapper $ins): int {
            /**
             * @var ConsoleClosureController $this
             */

            $input   = $this->input;
            $output  = $this->output;
            $cocoApp = $this->cocoApp;

            $result = $cocoApp->cron->getScheduleList();

            $table = new Table(new Symfony\Component\Console\Output\ConsoleOutput());

//            $table->setStyle('default');
//            $table->setStyle('box');
            $table->setStyle('box-double');
//            $table->setStyle('borderless');
//            $table->setStyle('compact');
//            $table->setStyle('symfony-style-guide');

            $table->setHeaders([
                'id',
                '表达式',
                '执行周期',
                '克重复',
                '上次运行时间',
                '上次运行历时',
                '下次运行时间',
                '下次运行剩余',
                '时区',
                '描述',
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

        //按当前时间，触发一次任务
        $command->addRoute('/cron/runAll', ConsoleControllerWrapper::closure($appName, function(ConsoleControllerWrapper $ins): int {
            /**
             * @var ConsoleClosureController $this
             */

            $input   = $this->input;
            $output  = $this->output;
            $cocoApp = $this->cocoApp;

            $cocoApp->cron->listen();

            return Command::SUCCESS;

        }), __FILE__, '按当前时间，触发一次任务');

        //强制执行指定 id 任务
        $command->addRoute('/cron/runById', ConsoleControllerWrapper::closure($appName, function(ConsoleControllerWrapper $ins): int {
            /**
             * @var ConsoleClosureController $this
             */

            $input   = $this->input;
            $output  = $this->output;
            $cocoApp = $this->cocoApp;

            $id = $ins->params['id'] ?? null;

            if (is_numeric($id))
            {
                $cocoApp->cron->runJobById($id);
            }

            return Command::SUCCESS;

        }), __FILE__, '强制执行指定 id 任务', [
            "id" => [
                "require"     => true,
                "description" => '任务id',
            ],
        ]);

    };