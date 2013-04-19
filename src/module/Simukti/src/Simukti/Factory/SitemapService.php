<?php
/**
 * Description of SitemapService
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
//use Zend\Cache\StorageFactory;
use Simukti\Service\Sitemap;

class SitemapService implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //$config          = $serviceLocator->get('config');
        $sitemapService  = new Sitemap;
        $sitemapService->setRouter($serviceLocator->get('router'));
        $sitemapService->setViewHelperPluginManager($serviceLocator->get('viewhelpermanager'));
        $sitemapService->setApi($serviceLocator->get('simukti-service-api'));
        return $sitemapService;
    }
}
