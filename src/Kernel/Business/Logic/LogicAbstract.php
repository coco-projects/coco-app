<?php

    namespace Coco\cocoApp\Kernel\Business\Logic;

    use \Coco\cocoApp\CocoApp;
    use Coco\cocoApp\Kernel\Traits\AppBaseTrait;
    use Coco\validate\Validate;

    abstract class LogicAbstract
    {
        use AppBaseTrait;

        const STATUS_ENABLE  = 1;
        const STATUS_DISABLE = 0;

        const STATUS_DELETED = 1;
        const STATUS_EXISTS  = 0;

        const FIELD_STATUS      = 'status';
        const FIELD_DELETED     = 'deleted';
        const FIELD_ORDER       = 'order';
        const FIELD_CREATE_TIME = 'create_time';
        const FIELD_DELETE_TIME = 'delete_time';

        /**
         * @var LogicAbstract[] $ins
         */
        private static array $ins = [];

        public Validate $validate;
        public mixed    $model;

        public static function getIns(): ?static
        {
            if (!isset(static::$ins[static::class]))
            {
                $this_ = static::$ins[static::class] = new static(CocoApp::getInstance());
                $this_->initModel();
                $this_->initValidate();

                $this_->init();
            }

            return static::$ins[static::class];
        }

        private function initModel(): void
        {
            $modelClassName = $this->makeAttrClass('Model');
            $this->model    = $modelClassName::getIns();
        }

        private function initValidate(): void
        {
            $modelClassName = $this->makeAttrClass('Validate');
            $this->validate = new $modelClassName();
        }

        private function makeAttrClass($attr): string
        {
            $classNameArray = explode('\\', static::class);

            $classNameArray[count($classNameArray) - 2] = $attr;

            $classNameArray[count($classNameArray) - 1] = strtr($classNameArray[count($classNameArray) - 1], [
                "Logic" => $attr,
            ]);

            //App\admin\Model\PeopleModel
            //App\admin\Validate\PeopleValidate
            return implode("\\", $classNameArray);
        }

        protected function init(): void
        {

        }

        public function error(array $data = [], array $msg = []): array
        {
            return [
                "is_success" => false,
                "data"       => $data,
                "msg"        => $msg,
            ];
        }

        public function success(array $data = [], array $msg = []): array
        {
            return [
                "is_success" => true,
                "data"       => $data,
                "msg"        => $msg,
            ];
        }

    }