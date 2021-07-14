<?php

namespace Kirill\Cash\Api\Data;


interface HistorySearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Gets collection items.
     *
     * @return HistoryInterface[] Array of collection items.
     */
    public function getItems();

    /**
     * Set collection items.
     *
     * @param HistoryInterface[]  $items
     * @return $this
     */
    public function setItems(array $items);
}
