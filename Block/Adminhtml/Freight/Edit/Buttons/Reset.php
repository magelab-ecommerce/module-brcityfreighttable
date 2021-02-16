<?php
namespace Magelab\BRCityFreightTable\Block\Adminhtml\Freight\Edit\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Reset
 * @package Magelab\BRCityFreightTable\Block\Adminhtml\Freight\Edit\Buttons
 */
class Reset implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Reset'),
            'class' => 'reset',
            'on_click' => 'location.reload();',
            'sort_order' => 30
        ];
    }
}
