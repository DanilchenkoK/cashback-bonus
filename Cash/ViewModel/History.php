<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kirill\Cash\ViewModel;

use Kirill\Cash\Model\ResourceModel\History\CollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class History extends DataObject implements ArgumentInterface
{
    /**
     * @var Session
     */
    private $customerSession;
    /**
     * @var CollectionFactory
     */
    private $historyCollectionFactory;

    /**
     * History constructor.
     *
     */
    public function __construct(
        CollectionFactory $historyCollectionFactory,
        Session $customerSession,
        array $data = [])
    {
        parent::__construct($data);

        $this->customerSession = $customerSession;
        $this->historyCollectionFactory = $historyCollectionFactory;
    }

    /**
     * @return mixed
     */
    public function getBonusHistory()
    {
        $historyCollection = $this->historyCollectionFactory->create();
        $historyCollection->addFieldToFilter('customer_id', $this->customerSession->getCustomer()->getId());
        return $historyCollection;
    }

}
