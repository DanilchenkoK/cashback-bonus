<?php

namespace Kirill\Cash\Block\Bonus;


class History extends \Magento\Framework\View\Element\Template
{

    protected $_template = 'Kirill_Cash::history.phtml';

    protected $customerSession;

    private $historyCollectionFactory;



    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Kirill\Cash\Model\ResourceModel\History\CollectionFactory $historyCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {

        $this->customerSession = $customerSession;
        $this->historyCollectionFactory = $historyCollectionFactory;

        parent::__construct($context, $data);

        $this->pageConfig->getTitle()->set(__('My Cashback'));
      }

      public function getBonusHistory(){
          $historyCollection = $this->historyCollectionFactory->create();
           $historyCollection->addFieldToFilter('customer_id', $this->customerSession->getCustomer()->getId());
         return $historyCollection;
      }


}
