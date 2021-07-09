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
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Payment\Block\Info\Instructions;
use Magento\Payment\Helper\Data;
use Magento\Payment\Model\InfoInterface;
use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Payment\Model\Method\Logger;
use Magento\Quote\Api\Data\CartInterface;

class Cashback extends AbstractMethod
{
    const PAYMENT_METHOD_CASHBACK_CODE = 'cashbackbonus';
    protected $_code = self::PAYMENT_METHOD_CASHBACK_CODE;
    protected $_infoBlockType = Instructions::class;
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

    public function getInstructions()
    {
        return trim($this->getConfigData('instructions'));
    }

    public function authorize(InfoInterface $payment, $amount)
    {
        if (!$this->canAuthorize()) {
            throw new LocalizedException(__('The authorize action is not available.'));
        }
        return $this;
    }

    public function isAvailable(CartInterface $quote = null)
    {
        try {
            if ($this->customerSesion->isLoggedIn() and
                $this->customerSesion->getCustomer()->getData('cashback') >= $quote->getSubtotal()) {
                return parent::isAvailable($quote);
            }
        } catch (Exception $ex) {

        }
        return null;
    }

}
