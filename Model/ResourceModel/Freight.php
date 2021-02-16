<?php

namespace Magelab\BRCityFreightTable\Model\ResourceModel;

/**
 * Class Page
 * @package Magelab\BRCityFreightTable\Model\ResourceModel
 */
class Freight extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init('magelab_brcityfreighttable', 'entity_id');
    }

}
