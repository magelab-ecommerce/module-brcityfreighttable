<?php

namespace Magelab\BRCityFreightTable\Model;

use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magelab\BRCityFreightTable\Api\Data\FreightSearchResultsInterfaceFactory;
use Magelab\BRCityFreightTable\Api\FreightRepositoryInterface;
use Magelab\BRCityFreightTable\Model\ResourceModel\Freight as FreightResource;
use Magelab\BRCityFreightTable\Model\ResourceModel\Freight\CollectionFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use \Psr\Log\LoggerInterface;

/**
 * Class FreightRepository
 * @package Magelab\BRCityFreightTable\Model
 */
class FreightRepository implements FreightRepositoryInterface
{
    /**
     * @var FreightResource
     */
    private $freightResource;

    /**
     * @var FreightFactory
     */
    private $freightFactory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var FreightSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var DateTime
     */
    protected $coreDate;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * FreightRepository constructor.
     * @param FreightResource $freightResource
     * @param FreightFactory $freightFactory
     * @param CollectionFactory $collectionFactory
     * @param FreightSearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        FreightResource $freightResource,
        FreightFactory $freightFactory,
        CollectionFactory $collectionFactory,
        FreightSearchResultsInterfaceFactory $searchResultsFactory,
        DateTime $coreDate,
        LoggerInterface $logger
    ) {
        $this->freightResource = $freightResource;
        $this->freightFactory = $freightFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->coreDate = $coreDate;
        $this->logger = $logger;
    }

    /**
     * @param \Magelab\BRCityFreightTable\Api\Data\FreightInterface $freight
     * @return mixed
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save(\Magelab\BRCityFreightTable\Api\Data\FreightInterface $freight)
    {
        $this->freightResource->save($freight);
        return $freight->getEntityId();
    }

    /**
     * @param $entityId
     * @return Freight
     * @throws NoSuchEntityException
     */
    public function getById($entityId)
    {
        if (!isset($this->_instances[$entityId])) {
            /** @var FreightInterface|AbstractModel $freight */
            $freight = $this->freightFactory->create();
            $this->freightResource->load($freight, $entityId);
            if (!$freight->getEntityId()) {
                throw new NoSuchEntityException(__('Freight does not exist'));
            }
            $this->instances[$entityId] = $freight;
        }

        return $this->instances[$entityId];
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magelab\BRCityFreightTable\Api\Data\FreightSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
        /** @var Magento\Framework\Api\SortOrder $sortOrder */
        foreach ((array)$searchCriteria->getSortOrders() as $sortOrder) {
            $field = $sortOrder->getField();
            $collection->addOrder(
                $field,
                $this->getDirection($sortOrder->getDirection())
            );

        }

        $collection->setCurFreight($searchCriteria->getCurrentFreight());
        $collection->setFreightSize($searchCriteria->getFreightSize());
        $collection->load();
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setCriteria($searchCriteria);

        $freights=[];
        foreach ($collection as $freight){
            $freights[] = $freight;
        }
        $searchResults->setItems($freights);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @param \Magelab\BRCityFreightTable\Api\Data\FreightInterface $freight
     * @return bool|mixed
     * @throws \Exception
     */
    public function delete(\Magelab\BRCityFreightTable\Api\Data\FreightInterface $freight)
    {
        if( $this->freightResource->delete($freight)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $direction
     * @return bool|string
     */
    private function getDirection($direction)
    {
        return $direction == SortOrder::SORT_ASC ?: SortOrder::SORT_DESC;
    }

    /**
     * @param $group
     * @param $collection
     */
    private function addFilterGroupToCollection($group, $collection)
    {
        $fields = [];
        $conditions = [];

        foreach($group->getFilters() as $filter){
            $condition = $filter->getConditionType() ?: 'eq';
            $field = $filter->getField();
            $value = $filter->getValue();
            $fields[] = $field;
            $conditions[] = [$condition=>$value];

        }
        $collection->addFieldToFilter($fields, $conditions);
    }

    /**
     * @param $id
     * @return bool|mixed
     * @throws NoSuchEntityException
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }
}
