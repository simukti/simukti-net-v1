<?php
/**
 * Description of AsyncControllerFactory
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Simukti\Controller\Async;

class AsyncController implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services    = $serviceLocator->getServiceLocator();
        $github      = $services->get('simukti-service-github');
        $flickr      = $services->get('simukti-service-flickr');
        $controller  = new Async;
        $controller->setGithubService($github);
        $controller->setFlickrService($flickr);
        return $controller;
    }
}
