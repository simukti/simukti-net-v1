<?php
/**
 * Description of FeedService
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
//use Zend\Cache\StorageFactory;
use Simukti\Service\Feed;

class FeedService implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //$config       = $serviceLocator->get('config');
        $feedService  = new Feed;
        $feedService->setRouter($serviceLocator->get('router'));
        $feedService->setViewHelperPluginManager($serviceLocator->get('viewhelpermanager'));
        $feedService->setApi($serviceLocator->get('simukti-service-api'));
        return $feedService;
    }
}
