<?php

namespace Magelab\BRCityFreightTable\Model\ResourceModel\Freight;

/**
 * Class Collection
 * @package Magelab\BRCityFreightTable\Model\ResourceModel\Freight
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init('Magelab\BRCityFreightTable\Model\Freight', 'Magelab\BRCityFreightTable\Model\ResourceModel\Freight');
    }

}
