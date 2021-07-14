<?php


namespace Kirill\Cash\Model;

use Exception;
use Kirill\Cash\Api\Data\HistoryInterface;
use Kirill\Cash\Api\Data\HistorySearchResultsInterfaceFactory;
use Kirill\Cash\Api\HistoryRepositoryInterface;
use Kirill\Cash\Model\ResourceModel\History as ResourceHistory;
use Kirill\Cash\Model\ResourceModel\History\CollectionFactory as HistoryCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;


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

    private $collectionProcessor;

    private $searchResultsFactory;

    /**
     * HistoryRepository constructor.
     * @param ResourceHistory $resource
     * @param HistoryFactory $historyFactory
     * @param HistoryCollectionFactory $historyCollectionFactory
     * @param CollectionProcessor $collectionProcessor
     * @param HistorySearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        ResourceHistory $resource,
        HistoryFactory $historyFactory,
        HistoryCollectionFactory $historyCollectionFactory,
        CollectionProcessor $collectionProcessor,
        HistorySearchResultsInterfaceFactory $searchResultsFactory
    )
    {
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
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
     * @param SearchCriteriaInterface $criteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $criteria)
    {

        $collection = $this->historyCollectionFactory->create();
        $searchResult = $this->searchResultsFactory->create();

        $this->collectionProcessor->process($criteria, $collection);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }


}
