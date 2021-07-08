<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kirill\Cash\Observer\Sales;

use Kirill\Cash\Helper\Data;
use Kirill\Cash\Model\HistoryFactory;
use Kirill\Cash\Model\ResourceModel\History;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\AlreadyExistsException;


class OrderSaveAfter implements ObserverInterface
{


    private $helper;
    private $historyFactory;
    private $historyResource;
    private $customerRepository;

    public function __construct(
        Data $helper,
        HistoryFactory $historyFactory,
        History $historyResource,
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
            if ($this->checkPayment($observer->getQuote()->getPayment()->getMethod())) {

                $cashback = $params['subtotal'] / 100 * $params['pct'];
                $customer_cashback = $this->getAttributeCashback($observer);

                $this->updateAttributeCashback($observer->getQuote()->getCustomer(), $cashback + $customer_cashback);
                $this->createHistoryRow('create', [
                    'customer_id' => $params['customer_id'],
                    'total_cash' => $cashback + $customer_cashback,
                ]);
            } else {

                $customer_cashback = $this->getAttributeCashback($observer);
                $this->updateAttributeCashback($observer->getQuote()->getCustomer(), $customer_cashback - $params['subtotal']);
                $this->createHistoryRow('debit', [
                    'customer_id' => $params['customer_id'],
                    'total_cash' => $customer_cashback - $params['subtotal']
                ]);
            }

    }


    public function getParams($observer)
    {
        return [
            'pct' => $this->helper->getGeneralConfig('pct'),
            'customer_id' => $observer->getQuote()->getCustomer()->getId(),
            'subtotal' => $observer->getQuote()->getSubtotal()
        ];

    }


    public function createHistoryRow($operation, $param)
    {
        $history = $this->historyFactory->create();
        $history->setCustomerId($param['customer_id']);
        $history->setOperation($operation);
        $history->setRemainCoin($param['total_cash']);
        $this->historyResource->save($history);
    }


    public function getAttributeCashback($observer)
    {
        try {
            return $observer->getQuote()->getCustomer()->getCustomAttributes()['cashback']->getValue();
        } catch (\Exception $e) {
            return 0;
        }

    }


    public function updateAttributeCashback($customer, $cashback)
    {
        $customer->setCustomAttribute('cashback', $cashback);
        $this->customerRepository->save($customer);

    }

    public function checkPayment($payment): bool
    {
        if ($payment == 'bonus') {
            return false;
        }
        return true;
    }

}
