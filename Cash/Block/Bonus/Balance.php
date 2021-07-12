<?php

namespace Kirill\Cash\Block\Bonus;


class Balance extends \Magento\Framework\View\Element\Template
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
     * Balance constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {

        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * @return int
     */
    public function getBonusBalance(){
        try{
          return $this->customerSession->getCustomer()->getCashback();
        }catch(\Exception $e){
            return 0;
        }

    }


}
