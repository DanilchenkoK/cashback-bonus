<?php

namespace Kirill\Cash\Block\Adminhtml\Bonus;

use Kirill\Cash\Model\ResourceModel\History\CollectionFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Customer\Controller\RegistryConstants;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Ui\Component\Layout\Tabs\TabInterface;

class Tab extends Template implements TabInterface
{
    protected $_coreRegistry;
    protected $_template = 'customer_bonus_history.phtml';
    private  $historyCollectionFactory;

    public function __construct(
        Context $context,
        CollectionFactory $historyCollectionFactory,
        Registry $registry,
        array $data = []
    )
    {
        $this->historyCollectionFactory = $historyCollectionFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }


    public function getCollectionHistory()
    {
        $historyCollection = $this->historyCollectionFactory->create();
        $historyCollection->addFieldToFilter('customer_id', $this->getCustomerId());
        return $historyCollection;
    }

    public function getCustomerId()
    {
        return $this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }

    public function getTabLabel()
    {
        return __('Cashback History');
    }

    public function getTabTitle()
    {
        return __('Cashback History');
    }

    public function canShowTab()
    {
        if ($this->getCustomerId()) {
            return true;
        }
        return false;
    }

    public function isHidden()
    {
        if ($this->getCustomerId()) {
            return false;
        }
        return true;
    }

    public function getTabClass()
    {
        return '';
    }

    public function getTabUrl()
    {
        return '';
        //  return $this->getUrl('cashback/bonus/history', ['_current' => true]);
    }

    public function isAjaxLoaded()
    {
        return false;
    }
}
