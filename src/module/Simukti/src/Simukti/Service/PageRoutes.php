<?php
/**
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Service;

use Zend\Mvc\Router\Http\TreeRouteStack;

class PageRoutes
{
    /**
     * @var \Zend\Mvc\Router\Http\TreeRouteStack
     */
    protected $router;

    /**
     * @var Api
     */
    protected $api;

    protected $routeOptions = array(
        'type'      => 'Literal',
        'options'   => array(
            'defaults' => array(
                'controller' => 'Simukti\Controller\Page',
                'action'     => 'view'
            ),
        ),
        'may_terminate' => true
    );
    
    public function setApi(Api $api)
    {
        $this->api = $api;
    }

    public function setRouter(TreeRouteStack $router)
    {
        if(! $this->router) {
            $this->router = $router;
        }
    }

    public function getRouter()
    {
        return $this->router;
    }
    
    /**
     * Buat routes baru dari data pages yang diambil dari API
     * 
     * @return  array new routes
     */
    public function getPageRoutes()
    {
        $pages  = $this->api->request('findAllPages');
        $pageRoutes = array();
        
        if($pages) {
            foreach($pages['data'] as $page) {
                $url   = trim($page['url']);
                // akhiran "/" mesti ditambahkan jika belum ada (saya mau semua URL berakhiran '/')
                $route = ( (substr($url, 0, 1) !== '/') && (substr($url, -1) !== '/') ) ? '/'.$url.'/' : $url;
                
                $options = $this->routeOptions;
                $options['options']['route'] = $route;
                $options['options']['defaults']['slug'] = $page['slug'];
                $options['priority'] = -1;
                $pageRoutes[$page['slug']] =  $options;
            }
        }
        return $pageRoutes;
    }
}
