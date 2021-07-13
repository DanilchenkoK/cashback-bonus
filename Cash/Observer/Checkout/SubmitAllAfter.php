<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kirill\Cash\Observer\Checkout;

use Exception;
use Kirill\Cash\Helper\Data;
use Kirill\Cash\Model\ResourceModel\History;
use Kirill\Cash\Model\HistoryFactory;
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
     * @var HistoryFactory
     */
    private $historyFactory;
    /**
     * @var History
     */
    private $historyResource;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * SubmitAllAfter constructor.
     * @param History $historyResource
     * @param Data $helper
     * @param HistoryFactory $historyFactory
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        History $historyResource,
        Data $helper,
        HistoryFactory $historyFactory,
        CustomerRepositoryInterface $customerRepository
    )
    {
        $this->customerRepository = $customerRepository;
        $this->historyResource = $historyResource;
        $this->historyFactory = $historyFactory;
        $this->helper = $helper;
    }

    /**
     * @throws AlreadyExistsException
     */
    public function execute(Observer $observer)
    {

        $params = $this->getParams($observer);
        $customer_cashback = $this->getAttributeCashback($observer);

        if ($this->checkPayment($observer->getQuote()->getPayment()->getMethod())) {

            $cashback = $params['subtotal'] / 100 * $params['pct'];
            $this->updateAttributeCashback($observer->getQuote()->getCustomer(), $cashback + $customer_cashback);
            $this->createHistoryRow('credited', [
                'customer_id' => $params['customer_id'],
                'total_cash' => $cashback + $customer_cashback,
                'sum' => $cashback
            ]);
        } else {
            $this->createHistoryRow('debit', [
                'customer_id' => $params['customer_id'],
                'total_cash' => $customer_cashback - $params['subtotal'],
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
     * @throws AlreadyExistsException
     */
    private function createHistoryRow($operation, $param)
    {
        $history = $this->historyFactory->create();
        $history->setCustomerId($param['customer_id']);
        $history->setOperation($operation);
        $history->setRemainCoin($param['total_cash']);
        $history->setSum($param['sum']);
        $this->historyResource->save($history);
    }

    /**
     * @param $observer
     * @return int
     */
    private function getAttributeCashback($observer)
    {
        try {
            return $observer->getQuote()->getCustomer()->getCustomAttributes()['cashback']->getValue();
        } catch (Exception $e) {
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
