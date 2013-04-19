<?php
/**
 * Description of ApiService
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Cache\StorageFactory;
use Simukti\Service\Api;
use SimuktiNetwork\Api as NetworkApi;

class ApiService implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config      = $serviceLocator->get('config');
        $cacheConfig = $config['cache']['request'];
        $apiService  = new Api(new NetworkApi($config['simukti']['simukti-api-key']));
        $apiService->setCache(StorageFactory::factory($cacheConfig));
        return $apiService;
    }
}
