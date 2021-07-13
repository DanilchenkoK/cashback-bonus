<?php



namespace Kirill\Cash\Api;

use Kirill\Cash\Api\Data\HistoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface HistoryRepositoryInterface
{

    /**
     * Save history
     *
     * @param HistoryInterface $history
     * @return HistoryInterface
     * @throws LocalizedException
     */
    public function save(Data\HistoryInterface $history);

    /**
     * Retrieve history
     *
     * @param int $historyId
     * @return HistoryInterface
     * @throws LocalizedException
     */
    public function getById($historyId);


    /**
     * Retrieve history by customer id
     *
     * @param $customerId
     * @return mixed
     */
    public function getListByCustomerId($customerId);

}
