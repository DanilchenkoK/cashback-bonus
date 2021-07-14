<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kirill\Cash\ViewModel;

use Kirill\Cash\Api\HistoryRepositoryInterface;
use Kirill\Cash\Model\ResourceModel\History\CollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class History extends DataObject implements ArgumentInterface
{
    /**
     * @var Session
     */
    private $customerSession;


    /**
     * @var HistoryRepository
     */
    private $historyRepository;

    /**
     * History constructor.
     * @param CollectionFactory $historyCollectionFactory
     * @param Session $customerSession
     * @param array $data
     */
    public function __construct(
        Session $customerSession,
        HistoryRepositoryInterface $historyRepository,
        array $data = [])
    {
        parent::__construct($data);

        $this->historyRepository = $historyRepository;
        $this->customerSession = $customerSession;
    }

    /**
     * @return mixed
     */
    public function getBonusHistory()
    {
        return $this->historyRepository->getListByCustomerId($this->customerSession->getId());
    }

}
