<?php

namespace Kirill\Cash\Block\Adminhtml\Bonus;

use Kirill\Cash\Model\ResourceModel\History\CollectionFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Phrase;
use Magento\Framework\View\Element\Template;
use Magento\Ui\Component\Layout\Tabs\TabInterface;

/**
 * Class Tab
 * @package Kirill\Cash\Block\Adminhtml\Bonus
 */
class Tab extends Template implements TabInterface
{

    protected $_template = 'customer_bonus_history.phtml';
    private $historyCollectionFactory;
    private $request;

    /**
     * Tab constructor.
     * @param Context $context
     * @param RequestInterface $request
     * @param CollectionFactory $historyCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        RequestInterface $request,
        CollectionFactory $historyCollectionFactory,
        array $data = []
    )
    {
        $this->historyCollectionFactory = $historyCollectionFactory;
        $this->request = $request;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getCollectionHistory()
    {
        $historyCollection = $this->historyCollectionFactory->create();
        $historyCollection->addFieldToFilter('customer_id', $this->getCustomerId());
        return $historyCollection;
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->request->getParam('id');
    }

    /**
     * @return Phrase|string
     */
    public function getTabLabel()
    {
        return __('Cashback History');
    }

    /**
     * @return Phrase|string
     */
    public function getTabTitle()
    {
        return __('Cashback History');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        if ($this->getCustomerId()) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        if ($this->getCustomerId()) {
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function getTabClass()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getTabUrl()
    {
        return '';
    }

    /**
     * @return false
     */
    public function isAjaxLoaded()
    {
        return false;
    }
}
