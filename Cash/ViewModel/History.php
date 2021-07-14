<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kirill\Cash\ViewModel;

use Kirill\Cash\Api\Data\HistoryInterface;
use Kirill\Cash\Api\HistoryRepositoryInterface;
use Kirill\Cash\Model\ResourceModel\History\CollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class History extends DataObject implements ArgumentInterface
{
    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var HistoryRepositoryInterface
     */
    private $historyRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * History constructor.
     * @param Session $customerSession
     * @param HistoryRepositoryInterface $historyRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $data
     */
    public function __construct(
        Session $customerSession,
        HistoryRepositoryInterface $historyRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $data = [])
    {
        parent::__construct($data);
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->historyRepository = $historyRepository;
        $this->customerSession = $customerSession;
    }

    /**
     * @return mixed
     */
    public function getBonusHistory()
    {
        $this->searchCriteriaBuilder
            ->addFilter(HistoryInterface::CUSTOMER_ID, $this->customerSession->getCustomer()->getId());
        return $this->historyRepository->getList($this->searchCriteriaBuilder->create())->getItems();
    }

}
