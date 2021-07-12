<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Kirill\Cash\Model\Payment;


use Exception;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Payment\Helper\Data;
use Magento\Payment\Model\InfoInterface;
use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Payment\Model\Method\Logger;
use Magento\Quote\Api\Data\CartInterface;

class Cashback extends AbstractMethod
{
    /**
     *
     */
    const PAYMENT_METHOD_CASHBACK_CODE = 'cashbackbonus';
    /**
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_CASHBACK_CODE;
    /**
     * @var bool
     */
    protected $_isOffline = true;
    /**
     * @var bool
     */
    protected $_canAuthorize = true;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * Cashback constructor.
     * @param Context $context
     * @param CustomerRepositoryInterface $customerRepository
     * @param Registry $registry
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param Data $paymentData
     * @param ScopeConfigInterface $scopeConfig
     * @param Logger $logger
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     * @param DirectoryHelper|null $directory
     */
    public function __construct(Context $context,
                                CustomerRepositoryInterface $customerRepository,
                                Registry $registry,
                                ExtensionAttributesFactory $extensionFactory,
                                AttributeValueFactory $customAttributeFactory,
                                Data $paymentData,
                                ScopeConfigInterface $scopeConfig,
                                Logger $logger,
                                AbstractResource $resource = null,
                                AbstractDb $resourceCollection = null,
                                array $data = [],
                                DirectoryHelper $directory = null)
    {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $resource,
            $resourceCollection,
            $data,
            $directory);

        $this->customerRepository = $customerRepository;
    }


    /**
     * @param InfoInterface $payment
     * @param float $amount
     * @return bool
     * @throws LocalizedException
     */
    public function authorize(InfoInterface $payment, $amount): bool
    {

        $customer = $this->customerRepository->getById($payment->getOrder()->getCustomerId());

        $subTotal = $payment->getOrder()->getSubtotal();
        $cashback = $customer->getCustomAttributes()['cashback']->getValue();

        $customer->setCustomAttribute('cashback', $cashback - $subTotal);
        $this->customerRepository->save($customer);

        return true;
    }


    /**
     * @param CartInterface|null $quote
     * @return bool
     */
    public function isAvailable(CartInterface $quote = null)
    {
        try {
            if ($quote->getCustomer()->getId() and
                $quote->getCustomer()->getCustomAttributes()['cashback']->getValue() >= $quote->getSubtotal()) {
                return true;
            }
        } catch (Exception $e) {
        }

        return false;
    }

}
