<?php

    namespace Coco\cocoApp\Kernel;

    class CocoAppConsts
    {
        //系统配置填充完，开始初始化服务器配置，ServerAbstract 构造器中
        public const CORE_SYSTEM_INIT_START = 'system_init_start';

        //console初始化之前，ConsoleServer initServer中
        public const CORE_CONSOLE_INIT_START = 'Console_init_start';
        //console初始化之后，ConsoleServer initServer中
        public const CORE_CONSOLE_INIT_END   = 'Console_init_end';

        //webserver初始化之前，WebSiteServer initServer中
        public const CORE_WEBSITESERVER_INIT_START = 'WebSiteServer_init_start';
        //webserver初始化之后，WebSiteServer initServer中
        public const CORE_WEBSITESERVER_INIT_END   = 'WebSiteServer_init_end';

        //服务器配置初始化完成，ServerAbstract 构造器中
        public const CORE_SYSTEM_INIT_END = 'system_init_end';

        //初始化逻辑链 ,在CocoApp initProcess 方法中 setOnStart 的回调中注册
        public const CORE_PROCESS_ON_START = 'onStart';

        //控制器执行之前，在AroundRun中间件中
        public const CORE_PROCESS_RUN_BEFORE = 'runProcessBefore';

        //执行逻辑链 , 在CocoApp initProcess 方法中 apendLogic 的回调中注册
        public const CORE_PROCESS_RUN = 'runProcess';

        //控制器执行之后，在AroundRun中间件中
        public const CORE_PROCESS_RUN_AFTER = 'runProcessAfter';

        //执行逻辑链结束 , 在CocoApp initProcess 方法中 setOnDone 的回调中注册
        public const CORE_PROCESS_ON_DONE = 'onDone';

        //逻辑链执行遇到 Exception , 在CocoApp initProcess 方法中 setOnCatch 的回调中注册
        public const CORE_PROCESS_ON_CATCH = 'onCatch';

        //逻辑链执行结果不为 false , 在CocoApp initProcess 方法中 setOnResultIsTrue 的回调中注册
        public const CORE_PROCESS_ON_RESULT_IS_TRUE = 'onResultIsTrue';

        //逻辑链执行结果为 false , 在CocoApp initProcess 方法中 setOnResultIsFalse 的回调中注册
        public const CORE_PROCESS_ON_RESULT_IS_FALSE = 'onResultIsFalse';

    }
