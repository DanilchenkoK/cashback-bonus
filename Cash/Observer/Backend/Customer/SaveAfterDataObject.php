<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kirill\Cash\Observer\Backend\Customer;


use Kirill\Cash\Model\HistoryFactory;
use Kirill\Cash\Model\HistoryRepository;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\CouldNotSaveException;

class SaveAfterDataObject implements ObserverInterface
{
    /**
     * @var HistoryFactory
     */
    private $historyFactory;
    /**
     * @var HistoryRepository
     */
    private $historyRepository;

    /**
     * SaveBefore constructor.
     * @param HistoryFactory $historyFactory
     * @param History $historyResource
     */
    public function __construct(
        HistoryFactory $historyFactory,
        HistoryRepository $historyRepository)
    {
        $this->historyFactory = $historyFactory;
        $this->historyRepository = $historyRepository;
    }

    /**
     * @param Observer $observer
     * @throws CouldNotSaveException
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
     * @throws CouldNotSaveException
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
