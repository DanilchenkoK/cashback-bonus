<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kirill\Cash\Observer\Backend\Customer;

use Kirill\Cash\Model\ResourceModel\History;
use Kirill\Cash\Model\HistoryFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\AlreadyExistsException;

class SaveAfterDataObject implements ObserverInterface
{
    private $historyFactory;
    private $historyResource;

    /**
     * SaveBefore constructor.
     * @param HistoryFactory $historyFactory
     * @param History $historyResource
     */
    public function __construct(
        HistoryFactory $historyFactory,
        History $historyResource)
    {
        $this->historyFactory = $historyFactory;
        $this->historyResource = $historyResource;
    }

    /**
     * @param Observer $observer
     * @throws AlreadyExistsException
     */
    public function execute(
        Observer $observer
    )
    {
        $new_cash_value = $observer->getCustomerDataObject()->getCustomAttributes()['cashback']->getValue();
        $old_cash_value = $observer->getOrigCustomerDataObject()->getCustomAttributes()['cashback']->getValue();

        $current_sum = $new_cash_value - $old_cash_value;
        $current_sum = $current_sum < 0 ? -$current_sum : $current_sum;

        $this->createHistoryRow([
            'customer_id' => $observer->getCustomerDataObject()->getId(),
            'total_cash' => $observer->getCustomerDataObject()->getCustomAttributes()['cashback']->getValue(),
            'sum' => $current_sum
        ]);
    }

    /**
     * @param $param
     * @throws AlreadyExistsException
     */
    private function createHistoryRow($param)
    {
        $history = $this->historyFactory->create();
        $history->setCustomerId($param['customer_id']);
        $history->setOperation('admin operation');
        $history->setSum($param['sum']);
        $history->setRemainCoin($param['total_cash']);
        $this->historyResource->save($history);
    }
}
