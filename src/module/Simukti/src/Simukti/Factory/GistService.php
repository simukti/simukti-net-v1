<?php
/**
 * Description of GistService
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Cache\StorageFactory;
use Simukti\Service\Gist;

class GistService implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config       = $serviceLocator->get('config');
        $cacheConfig  = $config['cache']['gist'];
        $gistService  = new Gist;
        $gistService->setCache(StorageFactory::factory($cacheConfig));
        return $gistService;
    }
}
