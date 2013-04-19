<?php
/**
 * Description of MiscController
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Simukti\Controller\Misc;

class MiscController implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services    = $serviceLocator->getServiceLocator();
        $sitemap     = $services->get('simukti-service-sitemap');
        $feed        = $services->get('simukti-service-feed');
        $controller  = new Misc;
        $controller->setSitemapService($sitemap);
        $controller->setFeedService($feed);
        return $controller;
    }
}
