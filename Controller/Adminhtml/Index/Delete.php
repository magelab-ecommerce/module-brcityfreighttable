<?php

namespace Magelab\BRCityFreightTable\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magelab\BRCityFreightTable\Api\FreightRepositoryInterface;

/**
 * Class Delete
 * @package Magelab\BRCityFreightTable\Controller\Adminhtml\Index
 */
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Magelab_BRCityFreightTable::delete';

    /**
     * @var FreightRepositoryInterface
     */
    private $freightRepository;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param FreightRepositoryInterface $freightRepository
     */
    public function __construct(
        Action\Context $context,
        FreightRepositoryInterface $freightRepository
    ) {
        parent::__construct($context);

        $this->freightRepository = $freightRepository;
    }

    /**
     * @return Redirect|\Magento\Framework\App\ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $this->freightRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('The freight has been deleted.'));

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a freight to delete.'));

        return $resultRedirect->setPath('*/*/');
    }
}
