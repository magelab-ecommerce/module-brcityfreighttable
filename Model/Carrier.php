<?php

namespace Magelab\BRCityFreightTable\Model;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magelab\BRCityFreightTable\Helper\CarrierHelper;

/**
* Custom shipping BRCityFreightTable
*/
class Carrier extends AbstractCarrier implements CarrierInterface
{
   /**
    * @var string
    */
   protected $_code = 'brcityfreighttable';

   /**
    * @var bool
    */
   protected $_isFixed = true;

   /**
    * @var \Magento\Shipping\Model\Rate\ResultFactory
    */
   private $rateResultFactory;

   /**
    * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
    */
   private $rateMethodFactory;

    /**
     * @var CarrierHelper
     */
   private $carrierHelper;

   /**
    * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
    * @param \Psr\Log\LoggerInterface $logger
    * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
    * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
    * @param array $data
    */
   public function __construct(
       \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
       \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
       \Psr\Log\LoggerInterface $logger,
       \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
       \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
       CarrierHelper $carrierHelper,
       array $data = []
   ) {
       parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);

       $this->rateResultFactory = $rateResultFactory;
       $this->rateMethodFactory = $rateMethodFactory;
       $this->carrierHelper = $carrierHelper;
   }

   /**
    * Custom Shipping Rates Collector
    *
    * @param RateRequest $request
    * @return \Magento\Shipping\Model\Rate\Result|bool
    */
   public function collectRates(RateRequest $request)
   {
       if (!$this->getConfigFlag('active')) {
           return false;
       }

       $zipcode = $request->getDestPostcode();

       if(!$zipcode) {
           return false;
       } else {

           try {
                $data = $this->carrierHelper->getCityMethodsByZipcode($zipcode);
           } catch (\Exception $e) {
                return false;
           }

           if(
               (isset($data['result']) && $data['result'] === false) ||
               (!isset($data['items'])) ||
               (isset($data['items']) && count($data['items']) == 0)
           ) {
               return false;
           }

           $items = (array) $data['items'];

           /** @var \Magento\Shipping\Model\Rate\Result $result */
           $result = $this->rateResultFactory->create();

           foreach($items as $item) {
               $carrierTitle = $item['title'] ? $item['title'] : $this->getConfigData('title');
               $methodTitle =  $item['estimative'] ? '(' . $item['estimative'] . ')' : $this->getConfigData('name');
               $shippingCost = isset($item['value']) ? $item['value'] : (float) $this->getConfigData('shipping_cost');

               /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
               $method = $this->rateMethodFactory->create();

               $method->setCarrier($this->_code);
               $method->setCarrierTitle($carrierTitle);

               $method->setMethod($this->carrierHelper::METHOD_PREFIX . $item['id']);
               $method->setMethodTitle($methodTitle);

               $method->setPrice($shippingCost);
               $method->setCost($shippingCost);

               $result->append($method);
           }
       }

       return $result;
   }

   /**
    * @return array
    */
   public function getAllowedMethods()
   {
       return $this->carrierHelper->getAllowedMethods();
   }
}
