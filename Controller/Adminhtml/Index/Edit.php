<?php

namespace Magelab\BRCityFreightTable\Controller\Adminhtml\Index;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magelab\BRCityFreightTable\Api\Data\FreightInterface;
use Magelab\BRCityFreightTable\Api\FreightRepositoryInterface;
use Magelab\BRCityFreightTable\Model\FreightFactory;

/**
 * Class Edit
 * @package Magelab\BRCityFreightTable\Controller\Adminhtml\Index
 */
class Edit extends Action
{
    const ADMIN_RESOURCE = 'Magelab_BRCityFreightTable::freight';

    /**
     * @var FreightRepositoryInterface
     */
    private $freightRepository;

    /**
     * @var FreightFactory
     */
    private $freightFactory;

    /**
     * Edit constructor.
     * @param Context $context
     * @param FreightRepositoryInterface $freightRepository
     * @param FreightFactory $freightFactory
     */
    public function __construct(
        Context $context,
        FreightRepositoryInterface $freightRepository,
        FreightFactory $freightFactory
    ) {
        parent::__construct($context);
        $this->freightRepository = $freightRepository;
        $this->freightFactory = $freightFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');

        if (!is_null($id)) {
            try {
                /** @var FreightInterface $model */
                $model = $this->freightRepository->getById($id);
            } catch (Exception $exception) {
                $this->messageManager->addErrorMessage(__('This freight no longer exists.'));
                $this->_redirect('*/*');

                return;
            }
        } else {
            $model = $this->freightFactory->create();
        }

        $this->_initAction();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Freights'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(
            $model->getId() ? __("Edit Freight '%1'", $model->getId()) : __('New Freight')
        );

        $breadcrumb = $id ? __('Edit Freight') : __('New Freight');
        $this->_addBreadcrumb($breadcrumb, $breadcrumb);
        $this->_view->renderLayout();
    }

    /**
     * @return $this
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Magelab_BRCityFreightTable::freight');

        return $this;
    }
}
