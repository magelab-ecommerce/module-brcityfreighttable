<?php

namespace Magelab\BRCityFreightTable\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magelab\BRCityFreightTable\Api\Data\FreightInterface;
use Magelab\BRCityFreightTable\Api\FreightRepositoryInterface;
use Magelab\BRCityFreightTable\Model\Freight;
use Magelab\BRCityFreightTable\Model\FreightFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Throwable;

/**
 * Class Save
 * @package Magelab\BRCityFreightTable\Controller\Adminhtml\Index
 */
class Save extends Action
{
    const ADMIN_RESOURCE = 'Magelab_BRCityFreightTable::save';

    /**
     * @var FreightRepositoryInterface
     */
    private $freightRepository;

    /**
     * @var FreightFactory
     */
    private $freightFactory;

    /**
     * Core Date
     *
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_coreDate;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param FreightRepositoryInterface $freightRepository
     * @param FreightFactory $freightFactory
     */
    public function __construct(
        Action\Context $context,
        FreightRepositoryInterface $freightRepository,
        FreightFactory $freightFactory,
        DateTime $coreDate
    ) {
        $this->_coreDate = $coreDate;
        parent::__construct($context);
        $this->FfeightRepository = $freightRepository;
        $this->FfeightFactory = $freightFactory;
    }

    /**
     * @return Redirect|\Magento\Framework\App\ResponseInterface|ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('entity_id');
        $data = $this->getRequest()->getParams();

        /** @var Freight $freight */
        if ($id) {
            $freight = $this->FfeightRepository->getById($id);
        } else {
            unset($data[FreightInterface::KEY_ID]);
            $freight = $this->FfeightFactory->create();
        }

        if(!isset($data['entity_id'])) {
            $data['created_at'] = $this->_coreDate->gmtDate();
        }

        $freight->setData($data);

        try {
            $this->FfeightRepository->save($freight);
            $this->messageManager->addSuccessMessage(__('Record saved successfully'));

            if (key_exists('back', $data) && $data['back'] == 'edit') {

                return $resultRedirect->setPath('*/*/edit', ['id' => $id, '_current' => true]);
            }

            return $resultRedirect->setPath('*/*/');
        } catch (Throwable $throwable) {
            $this->messageManager->addErrorMessage(__("Record not saved"));

            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
        }
    }
}
