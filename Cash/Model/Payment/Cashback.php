<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Kirill\Cash\Model\Payment;


use Exception;
use Magento\Customer\Model\Session;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Payment\Helper\Data;
use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Payment\Model\Method\Logger;
use Magento\Quote\Api\Data\CartInterface;

class Cashback extends AbstractMethod
{
    const PAYMENT_METHOD_CASHBACK_CODE = 'cashbackbonus';
    protected $_code = self::PAYMENT_METHOD_CASHBACK_CODE;
    protected $_isOffline = true;
    private $customerSesion;


    public function __construct(Context $context,
                                Session $customerSession,
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


        $this->customerSesion = $customerSession;
    }

    /**
     * @param CartInterface|null $quote
     * @return bool
     */

    public function isAvailable(CartInterface $quote = null)
    {
        try {
            if ($this->customerSesion->isLoggedIn() and
                $this->customerSesion->getCustomer()->getData('cashback') >= $quote->getSubtotal()) {
                return true;
            }
        } catch (Exception $e) {}

        return false;
    }

}
