<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Kirill\Cash\Block\Form;

/**
 * Block for Cash On Delivery payment method form
 */
class Cashback extends \Magento\OfflinePayments\Block\Form\AbstractInstruction
{
    /**
     * Cash on delivery template
     *
     * @var string
     */
    protected $_template = 'Kirill_Cash::cashback.phtml';
}
