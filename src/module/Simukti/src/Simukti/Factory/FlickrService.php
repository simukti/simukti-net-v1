<?php
/**
 * Description of FlickrService
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Cache\StorageFactory;
use Simukti\Service\Flickr as ServiceFlickr;

class FlickrService implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config       = $serviceLocator->get('config');
        $options      = $config['simukti']['networks']['flickr'];
        $cacheConfig  = $config['cache']['rest'];
        $cache        = StorageFactory::factory($cacheConfig);
        $serviceFlickr = new ServiceFlickr;
        $serviceFlickr->setCache($cache);
        $serviceFlickr->setOptions($options);
        return $serviceFlickr;
    }
}
