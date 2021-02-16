<?php

namespace Magelab\BRCityFreightTable\Model;

use Magento\Framework\Model\AbstractModel;
use Magelab\BRCityFreightTable\Api\Data\FreightInterface;

/**
 * Class Freight
 * @package Magelab\BRCityFreightTable\Model
 */
class Freight extends AbstractModel implements FreightInterface
{
    const CACHE_TAG = 'magelab_brcityfreighttable_freight';
    protected $_cacheTag = 'magelab_brcityfreighttable_freight';
    protected $_eventPrefix = 'magelab_brcityfreighttable_freight';

    protected function _construct()
    {
        $this->_init('Magelab\BRCityFreightTable\Model\ResourceModel\Freight');
    }

    /**
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return int
     */
    public function getEntityId()
    {
        return (int) $this->getData('entity_id');
    }

    /**
     * @param int $entityId
     * @return mixed|Freight
     */
    public function setEntityId($entityId)
    {
        return $this->setData('entity_id', $entityId);
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->getData('city');
    }

    /**
     * @param $city
     * @return mixed|Freight
     */
    public function setCity($city)
    {
        return $this->setData('city', $city);
    }

    /**
     * @return string
     */
    public function getUf()
    {
        return $this->getData('uf');
    }

    /**
     * @param $uf
     * @return mixed|Freight
     */
    public function setUf($uf)
    {
        return $this->setData('uf', $uf);
    }

    /**
     * @return double
     */
    public function getValue()
    {
        return $this->getData('value');
    }

    /**
     * @param $value
     * @return mixed|Freight
     */
    public function setValue($value)
    {
        return $this->setData('value', $value);
    }

    /**
     * @return string
     */
    public function getEstimative()
    {
        return $this->getData('estimative');
    }

    /**
     * @param $estimative
     * @return mixed|Freight
     */
    public function setEstimative($estimative)
    {
        return $this->setData('estimative', $estimative);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getData('title');
    }

    /**
     * @param $title
     * @return mixed|Freight
     */
    public function setTitle($title)
    {
        return $this->setData('title', $title);
    }

    /**
     * @return array|mixed|null
     */
    public function getIsEnabled()
    {
        return $this->getData('is_enabled');
    }

    /**
     * @param $isEnabled
     * @return mixed|Freight
     */
    public function setIsEnabled($isEnabled)
    {
        return $this->setData('is_enabled', $isEnabled);
    }

    /**
     * @return array|mixed|null
     */
    public function getCreatedAt()
    {
        return $this->getData('created_at');
    }

    /**
     * @param $createdAt
     * @return mixed|Freight
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData('created_at', $createdAt);
    }
}
