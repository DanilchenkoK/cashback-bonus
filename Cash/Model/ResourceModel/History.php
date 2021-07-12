<?php
namespace Kirill\Cash\Model\ResourceModel;

/**
 * Class History
 * @package Kirill\Cash\Model\ResourceModel
 */
class History extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * History constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }


    protected function _construct()
    {
        $this->_init('cashback_bonus_history', 'id');
    }

}
