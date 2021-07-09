<?php

namespace Kirill\Cash\Block\Bonus;


class Balance extends \Magento\Framework\View\Element\Template
{

    protected $_template = 'Kirill_Cash::history.phtml';

    protected $customerSession;

    private $historyCollectionFactory;



    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {

        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function getBonusBalance(){
        try{
          return $this->customerSession->getCustomer()->getCashback();
        }catch(\Exception $e){
            return 0;
        }

    }


}
