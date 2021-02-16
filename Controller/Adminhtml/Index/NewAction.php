<?php
namespace Magelab\BRCityFreightTable\Controller\Adminhtml\Index;

use Magento\Cms\Controller\Adminhtml\Block;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class NewAction
 * @package Magelab\BRCityFreightTable\Controller\Adminhtml\Index
 */
class NewAction extends Block
{
    const ADMIN_RESOURCE = 'Magelab_BRCityFreightTable::freight';

    /**
     * @return ResponseInterface|ResultInterface|void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
