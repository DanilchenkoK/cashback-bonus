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

class SubmitBefore implements ObserverInterface
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
        try {
            $params = $this->getParams($observer);
            $customer_cashback = $this->getAttributeCashback($observer);

            if ($this->checkPayment($observer->getQuote()->getPayment()->getMethod())) {

                $cashback = $params['subtotal'] / 100 * $params['pct'];
                $this->updateAttributeCashback($observer->getQuote()->getCustomer(), $cashback + $customer_cashback);
                $this->createHistoryRow('credited', [
                    'customer_id' => $params['customer_id'],
                    'total_cash' => $cashback + $customer_cashback,
                ]);
            } else {
                if ($customer_cashback - $params['subtotal'] >= 0) {
                    $this->updateAttributeCashback($observer->getQuote()->getCustomer(),);
                    $this->createHistoryRow('written off', [
                        'customer_id' => $params['customer_id'],
                        'total_cash' => $customer_cashback - $params['subtotal']
                    ]);
                }

            }
        } catch (Exception $e) {
        }


    }


    private function getParams($observer)
    {
        return [
            'pct' => $this->helper->getGeneralConfig('pct'),
            'customer_id' => $observer->getQuote()->getCustomer()->getId(),
            'subtotal' => $observer->getQuote()->getSubtotal()
        ];

    }


    private function createHistoryRow($operation, $param)
    {
        $history = $this->historyFactory->create();
        $history->setCustomerId($param['customer_id']);
        $history->setOperation($operation);
        $history->setRemainCoin($param['total_cash']);
        $this->historyResource->save($history);
    }


    private function getAttributeCashback($observer)
    {
        try {
            return $observer->getQuote()->getCustomer()->getCustomAttributes()['cashback']->getValue();
        } catch (Exception $e) {
            return 0;
        }

    }


    private function updateAttributeCashback($customer, $cashback)
    {
        $customer->setCustomAttribute('cashback', $cashback);
        $this->customerRepository->save($customer);

    }

    private function checkPayment($payment): bool
    {
        if ($payment == 'cashbackbonus') {
            return false;
        }
        return true;
    }
}
