<?php

    namespace Coco\cocoApp\Kernel\Business\Logic;

    use Coco\dataSource\base\CollectionSourceBase;
    use Coco\dataSource\filter\CollectionFilter;

    /**
     * @property CollectionSourceBase $source
     */
    abstract class CocoSourceCollectionLogicAbstract extends CocoSourceLogicAbstract
    {
        public function makeFilter(array $condition = []): CollectionFilter
        {
            $filter = new CollectionFilter();

            $this->evelCondition($condition, $filter);

            return $filter;
        }
    }