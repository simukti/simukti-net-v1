<?php
/**
 * Description of GithubService
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Cache\StorageFactory;
use Simukti\Service\Github;

class GithubService implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config       = $serviceLocator->get('config');
        $options      = $config['simukti']['networks']['github'];
        $cacheConfig  = $config['cache']['rest'];
        $githubService  = new Github;
        $githubService->setOptions($options);
        $githubService->setCache(StorageFactory::factory($cacheConfig));
        return $githubService;
    }
}
