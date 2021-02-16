<?php

namespace Magelab\BRCityFreightTable\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface FreightSearchResultsInterface
 * @package Magelab\BRCityFreightTable\Api\Data
 */
interface FreightSearchResultsInterface extends SearchResultsInterface
{

    /**
     * @return \Magento\Framework\Api\ExtensibleDataInterface[]
     */
    public function getItems();

    /**
     * @param array $items
     * @return FreightSearchResultsInterface
     */
    public function setItems(array $items);
}
