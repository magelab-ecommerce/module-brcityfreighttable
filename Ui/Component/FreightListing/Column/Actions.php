<?php

namespace Magelab\BRCityFreightTable\Ui\Component\FreightListing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magelab\BRCityFreightTable\Api\Data\FreightInterface;

/**
 * Class Actions
 * @package Magelab\BRCityFreightTable\Ui\Component\FreightListing\Column
 */
class Actions extends Column
{
    /** Url path */
    const PATH_EDIT = 'brcityfreighttable/index/edit';
    const PATH_DELETE = 'brcityfreighttable/index/delete';

    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface       $urlBuilder
     * @param array              $components
     * @param array              $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item[FreightInterface::KEY_ID])) {
                    $item[$name]['edit'] = [
                        'href'  => $this->urlBuilder->getUrl(self::PATH_EDIT, ['entity_id' => $item[FreightInterface::KEY_ID]]),
                        'label' => __('Edit')
                    ];
                    $item[$name]['delete'] = [
                        'href'    => $this->urlBuilder->getUrl(self::PATH_DELETE, ['entity_id' => $item[FreightInterface::KEY_ID]]),
                        'label'   => __('Delete'),
                        'confirm' => [
                            'title'   => __('Delete') . ' ' . $item[FreightInterface::KEY_ID],
                            'message' => __(
                                'Are you sure you wan\'t to delete a record?'
                            )
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
