<?php
namespace Kirill\Cash\Block\Adminhtml\Bonus;

use Magento\Customer\Controller\RegistryConstants;
use Magento\Ui\Component\Layout\Tabs\TabInterface;

class Tab extends \Magento\Framework\View\Element\Template implements TabInterface
{
    protected $_coreRegistry;
    protected $_template = 'customer_bonus_history.phtml';

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
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
