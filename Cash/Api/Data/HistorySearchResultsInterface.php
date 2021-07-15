<?php

namespace Kirill\Cash\Api\Data;


interface HistorySearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \Kirill\Cash\Api\Data\HistoryInterface[]
     */
    public function getItems();

    /**
     * Set collection items.
     *
     * @param array $items
     * @return $this
     */
    public function setItems(array $items);
}
