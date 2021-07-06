<?php
namespace Kirill\Cash\Model\ResourceModel\History;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'cashback_bonus_history_collection';
    protected $_eventObject = 'history_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Kirill\Cash\Model\History', 'Kirill\Cash\Model\ResourceModel\History');
    }

}
