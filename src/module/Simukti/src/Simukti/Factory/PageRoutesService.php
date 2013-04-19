<?php
/**
 * Description of PageRoutesService
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Simukti\Service\PageRoutes;

class PageRoutesService implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $router = $serviceLocator->get('router');
        $api    = $serviceLocator->get('simukti-service-api');
        $pageRoutesService = new PageRoutes;
        $pageRoutesService->setApi($api);
        $pageRoutesService->setRouter($router);
        return $pageRoutesService;
    }
}
