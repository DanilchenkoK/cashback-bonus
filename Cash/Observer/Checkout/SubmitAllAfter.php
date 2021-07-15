<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kirill\Cash\Observer\Checkout;


use Kirill\Cash\Api\HistoryRepositoryInterface;
use Kirill\Cash\Helper\Data;
use Kirill\Cash\Api\Data\HistoryInterfaceFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\State\InputMismatchException;

class SubmitAllAfter implements ObserverInterface
{
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var HistoryRepositoryInterface
     */
    private $historyRepository;
    /**
     * @var HistoryInterfaceFactory
     */
    private $historyFactory;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * SubmitAllAfter constructor.
     * @param Data $helper
     * @param HistoryInterfaceFactory $historyFactory
     * @param HistoryRepositoryInterface $historyRepository
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Data $helper,
        HistoryInterfaceFactory $historyFactory,
        HistoryRepositoryInterface $historyRepository,
        CustomerRepositoryInterface $customerRepository
    )
    {
        $this->customerRepository = $customerRepository;
        $this->historyFactory = $historyFactory;
        $this->historyRepository = $historyRepository;
        $this->helper = $helper;
    }

    /**
     * @throws AlreadyExistsException
     */
    public function execute(Observer $observer)
    {

        $params = $this->getParams($observer);
        $customerCashback = $this->getAttributeCashback($observer);

        if ($this->checkPayment($observer->getQuote()->getPayment()->getMethod())) {

            $cashback = $params['subtotal'] / 100 * $params['pct'];
            $this->updateAttributeCashback($observer->getQuote()->getCustomer(), $cashback + $customerCashback);
            $this->createHistoryRow('credited', [
                'customer_id' => $params['customer_id'],
                'total_cash' => $cashback + $customerCashback,
                'sum' => $cashback
            ]);
        } else {
            $this->createHistoryRow('debit', [
                'customer_id' => $params['customer_id'],
                'total_cash' => $customerCashback - $params['subtotal'],
                'sum' => $params['subtotal']
            ]);
        }

    }

    /**
     * @param $observer
     * @return array
     */
    private function getParams($observer)
    {
        return [
            'pct' => $this->helper->getGeneralConfig('pct'),
            'customer_id' => $observer->getQuote()->getCustomer()->getId(),
            'subtotal' => $observer->getQuote()->getSubtotal()
        ];

    }

    /**
     * @param $operation
     * @param $param
     */
    private function createHistoryRow($operation, $param)
    {

            $history = $this->historyFactory->create();
            $history->setCustomerId($param['customer_id']);
            $history->setOperation($operation);
            $history->setRemainCoin($param['total_cash']);
            $history->setSum($param['sum']);
            $this->historyRepository->save($history);


    }

    /**
     * @param $observer
     * @return float
     */
    private function getAttributeCashback($observer)
    {
        try {
            return $observer->getQuote()->getCustomer()->getCustomAttributes()['cashback']->getValue();
        } catch (\Exception $e) {
            return 0;
        }

    }

    /**
     * @param $customer
     * @param $cashback
     * @throws InputException
     * @throws LocalizedException
     * @throws InputMismatchException
     */
    private function updateAttributeCashback($customer, $cashback)
    {
        $customer->setCustomAttribute('cashback', $cashback);
        $this->customerRepository->save($customer);
    }

    /**
     * @param $payment
     * @return bool
     */
    private function checkPayment($payment): bool
    {
        return !($payment == 'cashbackbonus');
    }
}
