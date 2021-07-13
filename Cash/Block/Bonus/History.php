<?php

namespace Kirill\Cash\Block\Bonus;


use Kirill\Cash\Model\ResourceModel\History\CollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class History extends Template
{
    /**
     * History constructor.
     * @param Context $context
     * @param CollectionFactory $historyCollectionFactory
     * @param Session $customerSession
     * @param array $data
     */
    public function __construct(Context $context, array $data = [])
    {
        parent::__construct($context, $data);

        $this->pageConfig->getTitle()->set(__('My Cashback'));
    }


}
