<?php

    namespace Coco\cocoApp\Kernel\Business\Logic;

    use \Coco\cocoApp\CocoApp;
    use Coco\dataSource\abstracts\BaseFilter;
    use Coco\processManager\CallableLogic;
    use Coco\processManager\ProcessRegistry;

    abstract class CocoSourceLogicAbstract extends LogicAbstract
    {
        public mixed    $source;
        protected array $initRule = [];

        protected string $method_fetch_list;
        protected string $method_fetch_item;
        protected string $method_fetch_item_by_id;
        protected string $method_fetch_column;
        protected string $method_fetch_value;

        protected string $method_count;
        protected string $method_total_pages;

        protected function __construct(CocoApp $cocoApp)
        {
            $this->method_fetch_list       = $this->getCallClass() . ':fetchList';
            $this->method_fetch_item       = $this->getCallClass() . ':fetchItem';
            $this->method_fetch_item_by_id = $this->getCallClass() . ':fetchItemById';
            $this->method_fetch_column     = $this->getCallClass() . ':fetchColumn';
            $this->method_fetch_value      = $this->getCallClass() . ':fetchValue';

            $this->method_count       = $this->getCallClass() . ':count';
            $this->method_total_pages = $this->getCallClass() . ':totalPages';

            parent::__construct($cocoApp);
        }

        abstract protected function getCallClass();

        protected function init(): void
        {
            parent::init();
            $this->source = $this->model->source;
        }


        public function fetchList(BaseFilter $filter): array
        {
            $method   = $this->method_fetch_list;
            $registry = $this->initProcessRegistry($method, $filter);

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

        public function fetchItemById($id): array
        {
            $method   = $this->method_fetch_item_by_id;
            $registry = $this->initProcessRegistry($method, $this->makeFilter());

            $registry->id = $id;

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $condition = [
                    [
                        'field'     => 'id',
                        'operation' => 'eq',
                        'value'     => $registry->id,
                        'isEnable'  => 1,
                        'logic'     => 'and',
                    ],
                ];

                $registry->filter = $this->makeFilter($condition);
            }));

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->dbResult = $this->source->fetchItem($registry->filter);

            }, $method));

            $registry->executeLogics();

            if ($registry->getResult())
            {
                return $this->success($registry->dbResult, []);
            }
            else
            {
                return $this->error([], [
                    $method,
                    $registry->getResultMessage(),
                ]);
            }
        }


        public function fetchItem(BaseFilter $filter): array
        {
            $method   = $this->method_fetch_item;
            $registry = $this->initProcessRegistry($method, $filter);

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->dbResult = $this->source->fetchItem($registry->filter);

            }, $method));

            $registry->executeLogics();

            if ($registry->getResult())
            {
                return $this->success($registry->dbResult, []);
            }
            else
            {
                return $this->error([], [
                    $method,
                    $registry->getResultMessage(),
                ]);
            }
        }

        public function fetchColumn(string $field, BaseFilter $filter): array
        {
            $method   = $this->method_fetch_column;
            $registry = $this->initProcessRegistry($method, $filter);

            $registry->field = $field;

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->dbResult = $this->source->fetchColumn($registry->field, $registry->filter);

            }, $method));

            $registry->executeLogics();

            if ($registry->getResult())
            {
                return $this->success($registry->dbResult, []);
            }
            else
            {
                return $this->error([], [
                    $method,
                    $registry->getResultMessage(),
                ]);
            }
        }

        public function fetchValue(string $field, BaseFilter $filter): array
        {
            $method   = $this->method_fetch_value;
            $registry = $this->initProcessRegistry($method, $filter);

            $registry->field = $field;

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->dbResult = $this->source->fetchValue($registry->field, $registry->filter);

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


        public function count(BaseFilter $filter): array
        {
            $method   = $this->method_count;
            $registry = $this->initProcessRegistry($method, $filter);

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->dbResult = $this->source->count($registry->filter);

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

        public function totalPages(BaseFilter $filter): array
        {
            $method   = $this->method_total_pages;
            $registry = $this->initProcessRegistry($method, $filter);

            $registry->apendLogic(CallableLogic::getIns(function(ProcessRegistry $registry, CallableLogic $logic) {

                $registry->dbResult = $this->source->totalPages($registry->filter);

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


        public function deletedOnly(BaseFilter $filter): static
        {
            $condition = [
                [
                    'field'     => static::FIELD_DELETED,
                    'operation' => 'eq',
                    'value'     => static::STATUS_DELETED,
                    'isEnable'  => 1,
                    'logic'     => 'and',
                ],
            ];

            $this->evelCondition($condition, $filter);

            return $this;
        }

        public function notDeletedOnly(BaseFilter $filter): static
        {
            $condition = [
                [
                    'field'     => static::FIELD_DELETED,
                    'operation' => 'eq',
                    'value'     => static::STATUS_EXISTS,
                    'isEnable'  => 1,
                    'logic'     => 'and',
                ],
            ];

            $this->evelCondition($condition, $filter);

            return $this;
        }

        public function enabledOnly(BaseFilter $filter): static
        {
            $condition = [
                [
                    'field'     => static::FIELD_STATUS,
                    'operation' => 'eq',
                    'value'     => static::STATUS_ENABLE,
                    'isEnable'  => 1,
                    'logic'     => 'and',
                ],
            ];

            $this->evelCondition($condition, $filter);

            return $this;
        }

        public function disabledOnly(BaseFilter $filter): static
        {
            $condition = [
                [
                    'field'     => static::FIELD_STATUS,
                    'operation' => 'eq',
                    'value'     => static::STATUS_DISABLE,
                    'isEnable'  => 1,
                    'logic'     => 'and',
                ],
            ];

            $this->evelCondition($condition, $filter);

            return $this;
        }

        public function availableOnly(BaseFilter $filter): static
        {
            $condition = [
                [
                    'field'     => static::FIELD_DELETED,
                    'operation' => 'eq',
                    'value'     => static::STATUS_EXISTS,
                    'isEnable'  => 1,
                    'logic'     => 'and',
                ],
                [
                    'field'     => static::FIELD_STATUS,
                    'operation' => 'eq',
                    'value'     => static::STATUS_ENABLE,
                    'isEnable'  => 1,
                    'logic'     => 'and',
                ],
            ];

            $this->evelCondition($condition, $filter);

            return $this;
        }

        public function orderAsc($field, BaseFilter $filter): static
        {
            $filter->orderAsc($field);

            return $this;
        }

        public function orderDesc($field, BaseFilter $filter): static
        {
            $filter->orderDesc($field);

            return $this;
        }


        abstract public function makeFilter(array $condition = []): BaseFilter;

        protected function evelCondition(array $conditions, BaseFilter $filter): BaseFilter
        {
            $map = [
                "between_date"     => "whereTimeBetween",
                "not_between_date" => "whereTimeNotBetween",

                "lt_date"     => "whereTimeLt",
                "gt_date"     => "whereTimeGt",
                "egt_date"    => "whereTimeEgt",
                "elt_date"    => "whereTimeElt",
                "eq_date"     => "whereTimeEq",
                "not_eq_date" => "whereTimeNotEq",

                "null"     => "whereNull",
                "not_null" => "whereNotNull",

                "in"     => "whereIn",
                "not_in" => "whereNotIn",

                "status_on"  => "whereEq",
                "status_off" => "whereEq",

                "egt"      => "whereEgt",
                "elt"      => "whereElt",
                "lt"       => "whereLt",
                "gt"       => "whereGt",
                "like"     => "whereLike",
                "not_like" => "whereNotLike",
                "not_eq"   => "whereNotEq",
                "eq"       => "whereEq",
            ];

            //重置数组索引
            $conditions = array_values($conditions);

            //第一个逻辑必须是 and
            if (count($conditions))
            {
                $conditions[0]['logic'] = 'and';
            }

            foreach ($conditions as $k => $v)
            {
                switch ($v['operation'])
                {
                    case "eq_date" :
                    case "not_eq_date" :
                    case "lt_date" :
                    case "gt_date" :
                    case "egt_date" :
                    case "elt_date" :
                        break;

                    case "between_date" :
                    case "not_between_date" :
                        $v['value'] = explode(' - ', $v['value']);
                        break;

                    case "eq" :
                    case "not_eq" :
                        break;

                    case "lt" :
                    case "gt" :
                    case "egt" :
                    case "elt" :
                        break;

                    case "like" :
                    case "not_like" :
                        break;

                    case "null" :
                    case "not_null" :
                        break;

                    case "status_on" :
                        $v['value'] = 1;
                        break;
                    case "status_off" :
                        $v['value'] = 0;
                        break;

                    case "in" :
                    case "not_in" :
                        $v['value'] = explode(',', $v['value']);
                        break;

                    default :
                        break;
                }

                if ($v['isEnable'])
                {
                    $callback = [
                        $filter,
                        $map[$v['operation']],
                    ];

                    $callback($v['field'], $v['value'], $v['logic']);
                }
            }

            return $filter;
        }

        protected function initProcessRegistry(string $method, BaseFilter $filter): ProcessRegistry
        {
            $registry = new ProcessRegistry(!!$this->cocoApp->config->base->app_debug);

            $registry->filter = $filter;

            if (isset($this->initRule[$method]))
            {
                $this->initRule[$method]($registry);
            }

            return $registry;
        }

    }