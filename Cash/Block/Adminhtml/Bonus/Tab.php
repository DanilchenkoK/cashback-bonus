<?php

namespace Kirill\Cash\Block\Adminhtml\Bonus;

use Kirill\Cash\Api\Data\HistoryInterface;
use Kirill\Cash\Model\HistoryRepository;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Api\SearchCriteriaBuilder;
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
    /**
     * @var string
     */
    protected $_template = 'customer_bonus_history.phtml';
    /**
     * @var HistoryRepository
     */
    private $historyRepository;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * Tab constructor.
     * @param Context $context
     * @param RequestInterface $request
     * @param HistoryRepository $historyRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $data
     */
    public function __construct(
        Context $context,
        RequestInterface $request,
        HistoryRepository $historyRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $data = []
    )
    {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->historyRepository = $historyRepository;
        $this->request = $request;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getCollectionHistory()
    {
        $this->searchCriteriaBuilder
            ->addFilter(HistoryInterface::CUSTOMER_ID, $this->getCustomerId())
            ->setPageSize(5);
        return $this->historyRepository->getList($this->searchCriteriaBuilder->create())->getItems();
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
