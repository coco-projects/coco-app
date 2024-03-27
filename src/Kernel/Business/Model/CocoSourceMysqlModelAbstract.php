<?php

    namespace Coco\cocoApp\Kernel\Business\Model;

    use Coco\dataSource\source\MysqlSource;
    use Coco\dataSource\utils\MysqlHandler;

    /**
     * @property MysqlSource $source
     */
    abstract class CocoSourceMysqlModelAbstract extends CocoSourceModelAbstract
    {
        abstract public function getTableName(): string;

        protected function init(): void
        {
            $app = $this->cocoApp;

            $config = [
                'default' => 'default',

                'connections' => [
                    'default' => [
                        'hostname' => $app->config->db->host,
                        'database' => $app->config->db->name,
                        'username' => $app->config->db->user,
                        'password' => $app->config->db->pass,
                        'hostport' => $app->config->db->port,
                        'charset'  => $app->config->db->charset,

                        'type'              => $app->config->db->type,
                        'params'            => $app->config->db->params,
                        'fields_cache'      => $app->config->db->fields_cache,
                        'schema_cache_path' => $app->config->db->schema_cache_path,
                    ],
                ],
            ];

            $handler = MysqlHandler::getIns($config);

            $this->source = MysqlSource::getIns($handler->getDbManager(), 'default', $this->getTableName());

            $this->source->setCacheConfig($app->config->redis->host, $app->config->redis->port, $app->config->redis->password, $app->config->db->redis_cache_index, $app->config->db->redis_cache_prefix);

            $this->source->enableCache($app->config->db->enable_redis_cache);

            parent::init();
        }
    }