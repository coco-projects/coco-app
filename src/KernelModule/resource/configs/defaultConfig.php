<?php

    use Coco\constants\Consts;
    use Monolog\Handler\ErrorLogHandler;
    use Monolog\Handler\RotatingFileHandler;
    use Monolog\Level;

    return [
        "base" => [
            "app_debug"              => false,
            "timezone"               => 'Asia/Shanghai',
            "template"               => 'default',
            "error_reporting"        => E_ALL,
            "display_errors"         => true,
            "enable_mobile_template" => true,
        ],

        "db" => [
            "host" => '127.0.0.1',
            "name" => 'test',
            "user" => 'root',
            "pass" => '',
            "port" => 3306,

            'enable_redis_cache' => false,
            'redis_cache_index'  => 12,
            'redis_cache_prefix' => 'coco_mysql_cache',

            "charset"           => 'utf8',
            "type"              => 'mysql',
            'params'            => [],
            'fields_cache'      => true,
            'schema_cache_path' => 'data',
        ],

        "redis" => [
            "host"     => '127.0.0.1',
            "port"     => 6379,
            "password" => '',
        ],

        "symfony_cache" => [
            "db_index" => 10,
            "prefix"   => 'coco_cache',
        ],

        "log" => [

            "LineFormatter" => [
                "format"                     => null,
                "dateFormat"                 => null,
                "allowInlineLineBreaks"      => false,
                "ignoreEmptyContextAndExtra" => true,
                "includeStacktraces"         => false,
            ],

            "RotatingFileHandler" => [
                "filename"       => Consts::get('RUNTIME_PATH') . "/log/log.log",
                "maxFiles"       => 0,
                "level"          => Level::Debug,
                "bubble"         => true,
                "filePermission" => null,
                "useLocking"     => false,
                "dateFormat"     => RotatingFileHandler::FILE_PER_DAY,
                "filenameFormat" => '{filename}-{date}',
            ],

            "RedisHandler" => [
                "db_index" => 11,
                "key"      => "slim_logs",
                "level"    => Level::Debug,
                "bubble"   => true,
                "capSize"  => 0,
            ],

            "ErrorLogHandler" => [
                "messageType"    => ErrorLogHandler::OPERATING_SYSTEM,
                "level"          => Level::Debug,
                "bubble"         => true,
                "expandNewlines" => false,
            ],

            "SocketHandler" => [
                "connectionString"  => '',
                "level"             => Level::Debug,
                "bubble"            => true,
                "persistent"        => false,
                "timeout"           => 0,
                "writingTimeout"    => 10,
                "connectionTimeout" => null,
                "chunkSize"         => null,
            ],

            "StreamHandler" => [
                "stream"         => '',
                "level"          => Level::Debug,
                "bubble"         => true,
                "filePermission" => null,
                "useLocking"     => false,
            ],
        ],

        'Error' => [
            'error_Level' => E_ALL,
            "path"        => "/temp/cocoApp.log",
        ],
    ];