<?php
namespace Simukti;

use Zend\Mvc\MvcEvent;
use Simukti\View\Helper;

class Module
{
    public function onBootstrap(MvcEvent $event)
    {
        /**
         * @TODO getPageRoutes ini mestinya hanya terjadi jika request mengakses module Simukti
         *       bisa pake $eventManager->attach('route'), tapi ketika halaman diakses dapet error "no route name"
         */
        $application = $event->getApplication();
        $service     = $application->getServiceManager()->get('simukti-service-page-routes');
        $pageRoutes  = $service->getPageRoutes();
        $service->getRouter()->addRoutes($pageRoutes);
    }
    
    public function getConfig()
    {
        return include_once __DIR__ . '/config/module.config.php';
    }
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassmapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
        );
    }
    
    public function getViewHelperConfig()
    {
        /**
         * view_helpers ini anonymous dan jangan ditaruh di module.config.php, 
         * karena config cache bakal error ketika loading.
         */
        return array(
            'factories' => array(
                'flickr' => function ($services) {
                    $sm     = $services->getServiceLocator();
                    $flickrService = $sm->get('simukti-service-flickr');
                    $flickr = new Helper\Flickr;
                    $flickr->setFlickr($flickrService);
                    return $flickr;
                },
                'disqus' => function ($services) {
                    $sm     = $services->getServiceLocator();
                    $config = $sm->get('config');
                    $options = $config['simukti']['disqus'];
                    return new Helper\Disqus($options);
                },
                'livefyre' => function ($services) {
                    $sm     = $services->getServiceLocator();
                    $config = $sm->get('config');
                    $options = $config['simukti']['livefyre'];
                    return new Helper\Livefyre($options);
                },
                'google-analytics' => function ($services) {
                    $sm     = $services->getServiceLocator();
                    $config = $sm->get('config');
                    $options = $config['simukti']['analytics'];
                    $helper = new Helper\GoogleAnalytics();
                    $helper->setOptions($options);
                    return $helper;
                },
                'article-recent' => function ($services) {
                    $sm     = $services->getServiceLocator();
                    $api    = $sm->get('simukti-service-api');
                    $articleRecent = new Helper\ArticleRecent();
                    $articleRecent->setApi($api);
                    return $articleRecent;
                },
                'gist-parser' => function ($services) {
                    $sm = $services->getServiceLocator();
                    $gistService  = $sm->get('simukti-service-gist');
                    $gistHelper   = new Helper\GistParser();
                    $gistHelper->setService($gistService);
                    return $gistHelper;
                },
                'pages' => function ($services) {
                    $sm      = $services->getServiceLocator();
                    $config  = $sm->get('config');
                    $iconMap = $config['simukti']['icon-map'];
                    $pages = $sm->get('simukti-service-api')->request('findAllPageCategory');
                    $pageHelper = new Helper\Pages;
                    $pageHelper->setPages($pages)
                               ->setIconMaps($iconMap);
                    return $pageHelper;
                },
            )
        );
    }
}
