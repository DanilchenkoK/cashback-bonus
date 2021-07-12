<?php

namespace Kirill\Cash\Block\Bonus;


class History extends \Magento\Framework\View\Element\Template
{

    /**
     * @var string
     */
    protected $_template = 'Kirill_Cash::history.phtml';
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * @var \Kirill\Cash\Model\ResourceModel\History\CollectionFactory
     */
    private $historyCollectionFactory;


    /**
     * History constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Kirill\Cash\Model\ResourceModel\History\CollectionFactory $historyCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
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

    /**
     * @return mixed
     */
      public function getBonusHistory(){
          $historyCollection = $this->historyCollectionFactory->create();
           $historyCollection->addFieldToFilter('customer_id', $this->customerSession->getCustomer()->getId());
         return $historyCollection;
      }


}
