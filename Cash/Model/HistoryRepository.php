<?php


namespace Kirill\Cash\Model;

use Exception;
use Kirill\Cash\Api\Data\HistoryInterface;
use Kirill\Cash\Api\HistoryRepositoryInterface;
use Kirill\Cash\Model\ResourceModel\History as ResourceHistory;
use Kirill\Cash\Model\ResourceModel\History\CollectionFactory as HistoryCollectionFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

class HistoryRepository implements HistoryRepositoryInterface
{

    /**
     * @var ResourceHistory
     */
    protected $resource;

    /**
     * @var HistoryFactory
     */
    protected $historyFactory;

    /**
     * @var HistoryCollectionFactory
     */
    protected $historyCollectionFactory;


    /**
     * HistoryRepository constructor.
     * @param ResourceHistory $resource
     * @param HistoryFactory $historyFactory
     * @param HistoryCollectionFactory $historyCollectionFactory
     */
    public function __construct(
        ResourceHistory $resource,
        HistoryFactory $historyFactory,
        HistoryCollectionFactory $historyCollectionFactory
    )
    {
        $this->resource = $resource;
        $this->historyFactory = $historyFactory;
        $this->historyCollectionFactory = $historyCollectionFactory;
    }

    /**
     * @param HistoryInterface $history
     * @return HistoryInterface
     * @throws CouldNotSaveException
     */
    public function save(HistoryInterface $history)
    {
        try {
            $this->resource->save($history);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $history;
    }

    /**
     * @param int $historyId
     * @return HistoryInterface
     * @throws NoSuchEntityException
     */
    public function getById($historyId)
    {
        $history = $this->historyFactory->create();
        $this->resource->load($history, $historyId);
        if (!$historyId->getId()) {
            throw new NoSuchEntityException(__('History with id "%1" does not exist.', $historyId));
        }
        return $history;
    }

    /**
     * @param $customerId
     * @return mixed
     */
    public function getListByCustomerId($customerId)
    {
        $historyCollection = $this->historyCollectionFactory->create();
        $historyCollection->addFieldToFilter('customer_id', $customerId);
        return $historyCollection;
    }

}
