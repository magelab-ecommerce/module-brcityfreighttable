<?php

namespace Magelab\BRCityFreightTable\Controller\Adminhtml\Index;

use Magento\Framework\App\ResponseInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package Magelab\BRCityFreightTable\Controller\Adminhtml\Index
 */
class Index extends Action
{
    const ADMIN_RESOURCE = 'Magelab_BRCityFreightTable::range';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return Page|ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $manageEntity = __('Manage Freight');

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->addBreadcrumb($manageEntity, $manageEntity);
        $resultPage->addBreadcrumb($manageEntity, $manageEntity);
        $resultPage->getConfig()->getTitle()->prepend($manageEntity);

        return $resultPage;
    }
}
