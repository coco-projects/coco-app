<?php

    namespace Coco\cocoApp;

    class CocoAppConsts
    {
        //系统开始初始化 ,在 CocoApp __construct 最后注册
        public const CORE_SYSTEM_INIT_START = 'system_init_start';

        //系统初始化结束，应用开始执行,在CocoApp listen方法中最开始注册
        public const CORE_RUN_LOGIC_START = 'run_logic_start';

        //初始化逻辑链 ,在CocoApp initProcess 方法中 setOnStart 的回调中注册
        public const CORE_PROCESS_ON_START = 'onStart';

        //控制器执行之前
        public const CORE_PROCESS_RUN_BEFORE = 'runProcessBefore';

        //执行逻辑链 , 在CocoApp initProcess 方法中 apendLogic 的回调中注册
        public const CORE_PROCESS_RUN = 'runProcess';

        //控制器执行之后
        public const CORE_PROCESS_RUN_AFTER = 'runProcessAfter';

        //执行逻辑链结束 , 在CocoApp initProcess 方法中 setOnDone 的回调中注册
        public const CORE_PROCESS_ON_DONE = 'onDone';

        //逻辑链执行遇到 Exception , 在CocoApp initProcess 方法中 setOnCatch 的回调中注册
        public const CORE_PROCESS_ON_CATCH = 'onCatch';

        //逻辑链执行结果不为 false , 在CocoApp initProcess 方法中 setOnResultIsTrue 的回调中注册
        public const CORE_PROCESS_ON_RESULT_IS_TRUE = 'onResultIsTrue';

        //逻辑链执行结果为 false , 在CocoApp initProcess 方法中 setOnResultIsFalse 的回调中注册
        public const CORE_PROCESS_ON_RESULT_IS_FALSE = 'onResultIsFalse';

        //应用执行结束，应用开始执行,在CocoApp listen方法中最后注册
        public const CORE_RUN_LOGIC_DONE = 'run_logic_done';

    }
