<?php

namespace Magelab\BRCityFreightTable\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface FreightInterface
 * @package Magelab\BRCityFreightTable\Api\Data
 */
interface FreightInterface extends ExtensibleDataInterface
{
    const KEY_ID            = 'entity_id';
    const KEY_CITY          = 'city';
    const KEY_TITLE         = 'title';
    const KEY_UF            = 'uf';
    const KEY_ESTIMATIVE    = 'estimative';
    const KEY_VALUE         = 'value';
    const KEY_IS_ENABLED    = 'is_enabled';
    const KEY_CREATED_AT    = 'created_at';

    /**
     * @return mixed
     */
    public function getEntityId();

    /**
     * @param $entityId
     * @return mixed
     */
    public function setEntityId($entityId);

    /**
     * @return string
     */
    public function getCity();

    /**
     * @param $city
     * @return mixed
     */
    public function setCity($city);

    /**
     * @return string
     */
    public function getUf();

    /**
     * @param $uf
     * @return mixed
     */
    public function setUf($uf);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param $title
     * @return mixed
     */
    public function setTitle($title);

    /**
     * @return double
     */
    public function getValue();

    /**
     * @param $value
     * @return mixed
     */
    public function setValue($value);

    /**
     * @return string
     */
    public function getEstimative();

    /**
     * @param $estimative
     * @return mixed
     */
    public function setEstimative($estimative);

    /**
     * @return mixed
     */
    public function getIsEnabled();

    /**
     * @param $isEnabled
     * @return mixed
     */
    public function setIsEnabled($isEnabled);

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param $createdAt
     * @return mixed
     */
    public function setCreatedAt($createdAt);
}
