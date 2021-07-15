<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kirill\Cash\Observer\Backend\Customer;


use Kirill\Cash\Api\HistoryRepositoryInterface;
use Kirill\Cash\Api\Data\HistoryInterfaceFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;

class SaveAfterDataObject implements ObserverInterface
{
    /**
     * @var HistoryInterfaceFactory
     */
    private $historyFactory;
    /**
     * @var HistoryRepositoryInterface
     */
    private $historyRepository;


    /**
     * SaveAfterDataObject constructor.
     * @param HistoryInterfaceFactory $historyFactory
     * @param HistoryRepositoryInterface $historyRepository
     */
    public function __construct(
        HistoryInterfaceFactory $historyFactory,
        HistoryRepositoryInterface $historyRepository)
    {
        $this->historyFactory = $historyFactory;
        $this->historyRepository = $historyRepository;
    }

    /**
     * @param Observer $observer
     * @throws LocalizedException
     */
    public function execute(
        Observer $observer
    )
    {
        $this->createHistoryRow([
            'customer_id' => $observer->getCustomerDataObject()->getId(),
            'total_cash' => $observer->getCustomerDataObject()->getCustomAttributes()['cashback']->getValue(),
            'sum' => $this->getOperationSum($observer)
        ]);
    }

    /**
     * @param $observer
     * @return float|int
     */
    private function getOperationSum($observer)
    {
        $newCashValue = $observer->getCustomerDataObject()->getCustomAttributes()['cashback']->getValue();
        $oldCashValue = $observer->getOrigCustomerDataObject()->getCustomAttributes()['cashback']->getValue();
        $currentSum = $newCashValue - $oldCashValue;

        return $currentSum < 0 ? -$currentSum : $currentSum;
    }


    /**
     * @param $param
     * @throws LocalizedException
     */
    private function createHistoryRow($param)
    {
        $history = $this->historyFactory->create();
        $history->setCustomerId($param['customer_id']);
        $history->setOperation('admin operation');
        $history->setSum($param['sum']);
        $history->setRemainCoin($param['total_cash']);
        $this->historyRepository->save($history);
    }
}
