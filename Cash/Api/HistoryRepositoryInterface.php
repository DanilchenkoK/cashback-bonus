<?php


namespace Kirill\Cash\Api;

use Kirill\Cash\Api\Data\HistoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface HistoryRepositoryInterface
{

    /**
     * @param HistoryInterface $history
     * @return HistoryInterface
     */
    public function save(HistoryInterface $history);


    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return Data\HistorySearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);


}
