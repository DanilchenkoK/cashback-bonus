<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kirill\Cash\ViewModel;

use Magento\Customer\Model\Session;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Balance extends DataObject implements ArgumentInterface
{
private $customerSession;

    /**
     * Balance constructor.
     * @param Session $customerSession
     * @param array $data
     */
    public function __construct(Session $customerSession, array $data = [])
    {
        parent::__construct($data);
        $this->customerSession = $customerSession;
    }

    /**
     * @return float | int
     */
    public function getBonusBalance()
    {
        try {
            return $this->customerSession->getCustomer()->getCashback();
        } catch (\Exception $e) {
            return 0;
        }

    }

}
