<?php
namespace Magelab\BRCityFreightTable\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\ZendClientFactory;
use Magento\Framework\App\CacheInterface;
use Magento\Setup\Exception;
use Magelab\BRCityFreightTable\Model\ResourceModel\Freight\CollectionFactory as FreightCollectionFactory;

/**
 * Class CarrierHelper
 * @package Magelab\BRCityFreightTable\Helper
 */
class CarrierHelper extends AbstractHelper
{
    const CONFIG_SECTION_ID = 'carriers';
    const CONFIG_GENERAL_GROUP = 'brcityfreighttable';
    const CACHE_PREFIX = 'magelab_brcityfreighttable_';
    const METHOD_PREFIX = 'brcityfreighttable_';
    const ENDPOINT = 'https://viacep.com.br/ws/';
    const ENDPOINT_TYPE = '/json/';

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var ZendClientFactory
     */
    protected $_httpClientFactory;

    /**
     * @var PageCollectionFactory
     */
    protected $_freightCollectionFactory;

    /**
     * @var CacheInterface
     */
    protected $_cache;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * CarrierHelper constructor.
     * @param ZendClientFactory $httpClientFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param CacheInterface $cache
     * @param FreightCollectionFactory $freightCollectionFactory
     */
    public function __construct(
        ZendClientFactory $httpClientFactory,
        ScopeConfigInterface $scopeConfig,
        CacheInterface $cache,
        \Psr\Log\LoggerInterface $logger,
        FreightCollectionFactory $freightCollectionFactory
    ){
        $this->_cache = $cache;
        $this->_logger = $logger;
        $this->_httpClientFactory   = $httpClientFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->_freightCollectionFactory = $freightCollectionFactory;
    }

    /**
     * @param string $group
     * @param string $config
     * @return mixed
     */
    public function getConfig(string $group, string $config)
    {
        $configPath = $this->_getConfigPath($group, $config);
        return $this->_scopeConfig->getValue($configPath);
    }

    /**
     * @param string $group
     * @param string $config
     * @return string
     */
    private function _getConfigPath(string $group, string $config)
    {
        $configPathArr = [self::CONFIG_SECTION_ID, $group, $config];
        return implode('/', $configPathArr);
    }

    /**
     * @return bool
     */
    public function isEnable()
    {
        return (bool) $this->getConfig(self::CONFIG_GENERAL_GROUP, 'active');
    }

    /**
     * @param $zipcode
     * @return mixed
     * @throws Exception
     * @throws \Zend_Http_Client_Exception
     */
    public function getResponseFromViacep($zipcode)
    {
        $client = $this->_httpClientFactory->create();
        $client->setUri($this->_getEndpoint($zipcode));
        $client->setConfig(['timeout' => 300]);
        $client->setMethod(\Zend_Http_Client::GET);

        $this->_logger->info('------ Magelab - Carrier - Request -------');
        $this->_logger->info($this->_getEndpoint($zipcode));

        try {
            $responseBody = $client->request()->getBody();
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
//            throw new Exception($e->getMessage());
            return false;
        }

        $response = json_decode($responseBody);

        return $response;
    }

    /**
     * @param string $zipcode
     * @return array|string
     * @throws Exception
     * @throws \Zend_Http_Client_Exception
     */
    public function getCityMethodsByZipcode($zipcode)
    {
        $data = ['result' => false];
        $zipcode = $this->_threatZipcode($zipcode);

        if(!$zipcode || !$this->isEnable()) {
            return $data;
        }

        $identifier = $this->_getCacheIdentifier($zipcode);

        $this->_logger->info('------ Magelab - Carrier - Trying to retrive from cache ' . $identifier . ' -------');
        if($dataFromCache = $this->_cache->load($identifier)) {
            $this->_logger->info('------ Magelab - Carrier - Retrived from cache ' . $identifier . ' with success -------');
            $data = unserialize($dataFromCache);
        } else {

            $this->_logger->info('------ Magelab - Carrier - Could not retrive from cache ' . $identifier . ' -------');
            $response = $this->getResponseFromViacep($zipcode);

            $this->_logger->info('------ Magelab - Carrier - Response from viacep -------');
            $this->_logger->info(print_r($response, true));


            try{
                if($response !== false && $response->localidade) {
                    $collection = $this->_freightCollectionFactory->create();
                    $collection
                        ->addFieldToFilter('city', ['eq' => $response->localidade])
                        ->addFieldToFilter('uf', ['eq' => $response->uf])
                        ->addFieldToFilter('is_enabled', ['eq' => true]);
//                        ->setPageSize(1)
//                        ->setCurPage(1);

                    if($collection->getSize()) {
                        $this->_logger->info(print_r($collection->getData(),true));
                        $collectFreights = [];
                        foreach ($collection as $item) {
//
                            $collectFreights[] = [
                                'id' => $item->getEntityId(),
                                'city' => $item->getCity(),
                                'uf' => $item->getUf(),
                                'title' => $item->getTitle(),
                                'estimative' => $item->getEstimative(),
                                'value' => (float) $item->getValue(),
                                'status' => $item->getIsEnabled()
                            ];
                        }
                    }

                    if(!isset($collectFreights)) {
                        $this->cache->save($data, $identifier);
                        return $data;
                    } else {
                        $data = ['result' => true, 'items' => $collectFreights];
                    }

                    $this->_logger->info('------ Magelab - Carrier - Saving in cache ' . $identifier . ' -------');
                    $this->_cache->save(serialize($data), $identifier);
                }

            } catch (\Exception $e) {
                return $data;
            }
        }

        return $data;
    }

    public function getAllowedMethods()
    {
        $collection = $this->_freightCollectionFactory->create();
        $collection->addFieldToFilter('is_enabled', ['eq' => true]);
        $collectFreights = [];

        foreach ($collection as $item) {
            $collectFreights[self::METHOD_PREFIX . $item->getId()] = $item->getTitle();
        }

        return $collectFreights;
    }

    /**
     * @param $zipcode
     * @return string
     */
    protected function _getCacheIdentifier($zipcode)
    {
        return self::CACHE_PREFIX . $zipcode;
    }

    /**
     * @param $zipcode
     * @return string
     */
    protected function _getEndpoint($zipcode)
    {
        return self::ENDPOINT . $zipcode . self::ENDPOINT_TYPE;
    }

    /**
     * @param $zipcode
     * @return false|string|string[]
     */
    protected function _threatZipcode($zipcode)
    {
        $zipcode = str_replace(['-', '.'], '', trim($zipcode));
        $newZipcode = str_replace('-', '', trim($zipcode));


        if(strlen($newZipcode)==8){
            return $newZipcode;
        } else {
            return false;
        }
    }

}
