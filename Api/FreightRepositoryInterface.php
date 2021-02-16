<?php

namespace Magelab\BRCityFreightTable\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magelab\BRCityFreightTable\Api\Data\FreightInterface;

/**
 * Interface FreightRepositoryInterface
 * @package Magelab\BRCityFreightTable\Api
 */
interface FreightRepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param FreightInterface $page
     * @return mixed
     */
    public function save(FreightInterface $page);

    /**
     * @param FreightInterface $page
     * @return mixed
     */
    public function delete(FreightInterface $page);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param $entityId
     * @return mixed
     */
    public function deleteById($entityId);

}
