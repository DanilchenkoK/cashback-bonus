<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kirill\Cash\Model;

use Kirill\Cash\Api\Data\HistorySearchResultsInterface;

use Magento\Framework\Api\SearchResults;

/**
 * Class HistorySearchResult
 * @package Kirill\Cash\Model
 */
class HistorySearchResults extends SearchResults implements  HistorySearchResultsInterface
{
}
