<?php
namespace Magelab\BRCityFreightTable\Block\Adminhtml\Freight\Edit\Buttons;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magelab\BRCityFreightTable\Api\FreightRepositoryInterface;

/**
 * Class Generic
 * @package Magelab\BRCityFreightTable\Block\Adminhtml\Freight\Edit\Buttons
 */
class Generic
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var FreightRepositoryInterface
     */
    protected $freightRepository;

    /**
     * Generic constructor.
     * @param Context $context
     * @param FreightRepositoryInterface $freightRepository
     */
    public function __construct(
        Context $context,
        FreightRepositoryInterface $freightRepository
    ) {
        $this->context = $context;
        $this->freightRepository = $freightRepository;
    }

    /**
     * @return |null
     */
    public function getId()
    {
        try {
            return $this->freightRepository->getById(
                $this->context->getRequest()->getParam('entity_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
