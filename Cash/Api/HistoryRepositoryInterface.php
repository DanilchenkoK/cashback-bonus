<?php


namespace Kirill\Cash\Api;

use Kirill\Cash\Api\Data\HistoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;


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
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria);



}
