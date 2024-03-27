<?php

    namespace Coco\cocoApp\Kernel\Business\Logic;

    use \Coco\cocoApp\CocoApp;
    use Coco\dataSource\filter\MysqlFilter;
    use Coco\dataSource\source\MysqlSource;
    use Coco\processManager\CallableLogic;
    use Coco\processManager\ProcessRegistry;
    use Coco\validate\Validate;
    use Godruoyi\Snowflake\Snowflake;

    /**
     * @property MysqlSource $source
     */
    abstract class CocoSourceMysqlLogicAbstract extends CocoSourceLogicAbstract
    {
        protected string $method_add;
        protected string $method_add_batch;
        protected string $method_update;
        protected string $method_delete;

        protected string $method_update_field;
        protected string $method_update_by_id;

        protected string $method_soft_delete;
        protected string $method_unsoft_delete;

        protected string $method_status_enable;
        protected string $method_status_disable;

        protected string $method_fetch_recycle;

        protected function __construct(CocoApp $cocoApp)
        {

            $this->method_add       = $this->getCallClass() . ':add';
            $this->method_add_batch = $this->getCallClass() . ':addBatch';
            $this->method_update    = $this->getCallClass() . ':update';
            $this->method_delete    = $this->getCallClass() . ':delete';

            $this->method_update_field = $this->getCallClass() . ':updateField';
            $this->method_update_by_id = $this->getCallClass() . ':updateById';

            $this->method_soft_delete   = $this->getCallClass() . ':softDelete';
            $this->method_unsoft_delete = $this->getCallClass() . ':unsoftDelete';

            $this->method_status_enable  = $this->getCallClass() . ':statusEnable';
            $this->method_status_disable = $this->getCallClass() . ':statusDisable';

            $this->method_fetch_recycle = $this->getCallClass() . ':recycle';

            parent::__construct($cocoApp);
        }

        public function add(array $data): array
        {
            $method   = $this->method_add;
            $registry = $this->initProcessRegistry($method, $this->makeFilter());

            $registry->data = $data;

            //验证数据
            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $res = $this->validate->setData($registry->data)->setScene(Validate::SCENE_ADD)->check();

                if (!$res)
                {
                    $logic->setDebugMsg(json_encode($this->validate->getAllErrorMsg(), 256));

                    return false;
                }

            }));

            //填充id
            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $snowflake                                 = new Snowflake();
                $registry->id                              = $registry->data['id'] = $snowflake->id();
                $registry->data[static::FIELD_CREATE_TIME] = time();

            }));

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->dbResult = $this->source->insert($registry->data);

            }, $method));

            $registry->executeLogics();

            if ($registry->getResult())
            {
                return $this->success(["value" => $registry->dbResult,], []);
            }
            else
            {
                return $this->error([], [
                    $method,
                    $registry->getResultMessage(),
                ]);
            }
        }

        public function addBatch(array $data): array
        {
            $method   = $this->method_add_batch;
            $registry = $this->initProcessRegistry($method, $this->makeFilter());

            $registry->data = $data;

            //验证数据
            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->data_to_insert = [];
                $registry->data_error     = [];
                $snowflake                = new Snowflake();

                foreach ($registry->data as $k => $v)
                {
                    $res = $this->validate->setData($v)->setScene(Validate::SCENE_ADD)->check();

                    if (!$res)
                    {
                        $registry->data_error[] = [
                            "data" => $v,
                            "msg"  => $this->validate->getAllErrorMsg(),
                        ];
                    }
                    else
                    {
                        $v['id']                      = $snowflake->id();
                        $v[static::FIELD_CREATE_TIME] = time();

                        $registry->data_to_insert[] = $v;
                    }
                }

            }));

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->dbResult = $this->source->insertAll($registry->data_to_insert);

            }, $method));

            $registry->executeLogics();

            if ($registry->getResult())
            {
                return $this->success(["value" => $registry->dbResult,], [
                    $method,
                    json_encode($registry->data_error, 256),
                ]);
            }
            else
            {
                return $this->error([], [
                    $method,
                    $registry->getResultMessage(),
                ]);
            }
        }

        public function update(array $data, MysqlFilter $filter = null): array
        {
            $method   = $this->method_update;
            $registry = $this->initProcessRegistry($method, $filter);

            $registry->data = $data;

            //验证数据
            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $res = $this->validate->setData($registry->data)->setScene(Validate::SCENE_EDIT)->check();

                if (!$res)
                {
                    $logic->setDebugMsg(json_encode($this->validate->getAllErrorMsg(), 256));

                    return false;
                }

            }));

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->dbResult = $this->source->update($registry->data, $registry->filter);

            }, $method));

            $registry->executeLogics();

            if ($registry->getResult())
            {
                return $this->success(["value" => $registry->dbResult,], []);
            }
            else
            {
                return $this->error([], [
                    $method,
                    $registry->getResultMessage(),
                ]);
            }
        }

        public function delete(MysqlFilter $filter = null): array
        {
            $method   = $this->method_delete;
            $registry = $this->initProcessRegistry($method, $filter);

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->dbResult = $this->source->delete($registry->filter);

            }, $method));

            $registry->executeLogics();

            if ($registry->getResult())
            {
                return $this->success(["value" => $registry->dbResult,], []);
            }
            else
            {
                return $this->error([], [
                    $method,
                    $registry->getResultMessage(),
                ]);
            }
        }


        public function updateField(string|int $ids, string $field, string|int $value): array
        {
            $method   = $this->method_update_field;
            $registry = $this->initProcessRegistry($method, $this->makeFilter());

            $registry->ids   = $ids;
            $registry->value = $value;
            $registry->field = $field;

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $condition = [
                    [
                        'field'     => 'id',
                        'operation' => 'in',
                        'value'     => $registry->ids,
                        'isEnable'  => 1,
                        'logic'     => 'and',
                    ],
                ];

                $registry->filter = $this->makeFilter($condition);

            }));

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->dbResult = $this->source->update([
                    $registry->field => $registry->value,
                ], $registry->filter);

            }, $method));

            $registry->executeLogics();

            if ($registry->getResult())
            {
                return $this->success(["value" => $registry->dbResult,], []);
            }
            else
            {
                return $this->error([], [
                    $method,
                    $registry->getResultMessage(),
                ]);
            }
        }


        public function updateByIds(string|int $ids, array $data): array
        {
            $method   = $this->method_update_by_id;
            $registry = $this->initProcessRegistry($method, $this->makeFilter());

            $registry->data = $data;
            $registry->ids  = $ids;

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $condition = [
                    [
                        'field'     => 'id',
                        'operation' => 'in',
                        'value'     => $registry->ids,
                        'isEnable'  => 1,
                        'logic'     => 'and',
                    ],
                ];

                $registry->filter = $this->makeFilter($condition);
            }));

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->dbResult = $this->source->update($registry->data, $registry->filter);

            }, $method));

            $registry->executeLogics();

            if ($registry->getResult())
            {
                return $this->success(["value" => $registry->dbResult,], []);
            }
            else
            {
                return $this->error([], [
                    $method,
                    $registry->getResultMessage(),
                ]);
            }
        }

        public function softDelete(MysqlFilter $filter = null): array
        {
            $method   = $this->method_soft_delete;
            $registry = $this->initProcessRegistry($method, $filter);

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->dbResult = $this->source->update([
                    static::FIELD_DELETED     => static::STATUS_DELETED,
                    static::FIELD_DELETE_TIME => time(),

                ], $registry->filter);

            }, $method));

            $registry->executeLogics();

            if ($registry->getResult())
            {
                return $this->success(["value" => $registry->dbResult,], []);
            }
            else
            {
                return $this->error([], [
                    $method,
                    $registry->getResultMessage(),
                ]);
            }
        }

        public function unsoftDelete(MysqlFilter $filter = null): array
        {
            $method   = $this->method_unsoft_delete;
            $registry = $this->initProcessRegistry($method, $filter);

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->dbResult = $this->source->update([
                    static::FIELD_DELETED     => static::STATUS_EXISTS,
                    static::FIELD_DELETE_TIME => 0,
                ], $registry->filter);

            }, $method));

            $registry->executeLogics();

            if ($registry->getResult())
            {
                return $this->success(["value" => $registry->dbResult,], []);
            }
            else
            {
                return $this->error([], [
                    $method,
                    $registry->getResultMessage(),
                ]);
            }
        }


        public function statusEnable(MysqlFilter $filter = null): array
        {
            $method   = $this->method_status_enable;
            $registry = $this->initProcessRegistry($method, $filter);

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->dbResult = $this->source->update([
                    static::FIELD_STATUS => static::STATUS_ENABLE,
                ], $registry->filter);

            }, $method));

            $registry->executeLogics();

            if ($registry->getResult())
            {
                return $this->success(["value" => $registry->dbResult,], []);
            }
            else
            {
                return $this->error([], [
                    $method,
                    $registry->getResultMessage(),
                ]);
            }
        }

        public function statusDisable(MysqlFilter $filter = null): array
        {
            $method   = $this->method_status_disable;
            $registry = $this->initProcessRegistry($method, $filter);

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->dbResult = $this->source->update([
                    static::FIELD_STATUS => static::STATUS_DISABLE,
                ], $registry->filter);

            }, $method));

            $registry->executeLogics();

            if ($registry->getResult())
            {
                return $this->success(["value" => $registry->dbResult,], []);
            }
            else
            {
                return $this->error([], [
                    $method,
                    $registry->getResultMessage(),
                ]);
            }
        }


        public function recycle(MysqlFilter $filter = null): array
        {
            $method   = $this->method_fetch_recycle;
            $registry = $this->initProcessRegistry($method, $filter);

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $this->deletedOnly($registry->filter);

            }));

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->dbResult = $this->source->fetchList($registry->filter);

            }, $method));

            $registry->executeLogics();

            if ($registry->getResult())
            {
                return $this->success($registry->dbResult->all(), []);
            }
            else
            {
                return $this->error([], [
                    $method,
                    $registry->getResultMessage(),
                ]);
            }
        }


        public function makeFilter(array $condition = []): MysqlFilter
        {
            $filter = new MysqlFilter();

            $this->evelCondition($condition, $filter);

            return $filter;
        }

    }