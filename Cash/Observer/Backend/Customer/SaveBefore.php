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

class SaveBefore implements ObserverInterface
{
    private $historyFactory;
    private $historyResource;


    public function __construct(
        HistoryFactory $historyFactory,
        History $historyResource)
    {
        $this->historyFactory = $historyFactory;
        $this->historyResource = $historyResource;
    }

    /**
     * Execute observer
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(
        Observer $observer
    )
    {
        $this->createHistoryRow('admin operation', [
            'customer_id' => $observer->getCustomer()->getId(),
            'total_cash' => $observer->getCustomer()->getCashback()
        ]);
    }


    private function createHistoryRow($operation, $param)
    {
        $history = $this->historyFactory->create();
        $history->setCustomerId($param['customer_id']);
        $history->setOperation($operation);
        $history->setRemainCoin($param['total_cash']);
        $this->historyResource->save($history);
    }
}
