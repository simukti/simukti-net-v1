<?php
/**
 * Description of Sitemap
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Service;

use \DateTime;
use \DOMDocument;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\View\HelperPluginManager;

class Sitemap extends BaseService
{
    const SITEMAP_NS       = 'http://www.sitemaps.org/schemas/sitemap/0.9';
    const SITEMAP_XSD      = 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd';
    const SITEMAP_INSTANCE = 'http://www.w3.org/2001/XMLSchema-instance';
    
    const DAILY     = 'daily';
    const WEEKLY    = 'weekly';
    const MONTHLY   = 'monthly';
    const YEARLY    = 'yearly';
    
    protected $_domInstance;
    
    /**
     * @var \Simukti\Service\Api
     */
    protected $api;
    
    /**
     * @var \Zend\Mvc\Router\Http\TreeRouteStack
     */
    protected $router;
    
    /**
     * @var \Zend\View\HelperPluginManager
     */
    protected $view;
    
    public function setViewHelperPluginManager(HelperPluginManager $view)
    {
        $this->view = $view;
    }
    
    public function setApi(Api $api)
    {
        $this->api = $api;
    }
    
    public function setRouter(TreeRouteStack $router)
    {
        $this->router = $router;
    }
    
    public function getServerUrl()
    {
        $serverUrlHelper = $this->view->get('serverUrl');
        return $serverUrlHelper->getScheme() . '://' . $serverUrlHelper->getHost();
    }
    
    /**
     * @param   boolean $cacheSitemap
     * @return  string XML
     */
    public function getSitemap($cacheSitemap = PRODUCTION)
    {
        $sitemap = $this->createSitemap();
        return $sitemap;
    }
    
    protected function createSitemap()
    {
        $dom = $this->getDOMInstance();
        
        $urlSet = $dom->createElementNS(self::SITEMAP_NS, 'urlset');

        $instance = $dom->createAttribute('xmlns:xsi');
        $urlSet->appendChild($instance);
        $instance_text = $dom->createTextNode(self::SITEMAP_INSTANCE);
        $instance->appendChild($instance_text);
        
        $schema = $dom->createAttribute('xsi:schemaLocation');
        $urlSet->appendChild($schema);
        $location = $dom->createTextNode(self::SITEMAP_XSD);
        $schema->appendChild($location);
        
        $dom->appendChild($urlSet);
        
        // site
        foreach ($this->buildSite() as $item) {
            $urlSet->appendChild($item);
        }
        // pages
        foreach($this->buildPages() as $tag) {
            $urlSet->appendChild($tag);
        }
        // article tags
        foreach($this->buildTags() as $tag) {
            $urlSet->appendChild($tag);
        }
        // articles
        foreach($this->buildArticles() as $article) {
            $urlSet->appendChild($article);
        }
        
        $dom->preserveWhiteSpace = false; 
        $dom->formatOutput = true;
        
        return $dom->saveXML();
    }
    
    protected function buildSite()
    {
        $site = array();
        $site[] = $this->_createSitemapUrl($this->getServerUrl() . $this->router->assemble(array(), array('name' => 'home')));
        $site[] = $this->_createSitemapUrl($this->getServerUrl() . $this->router->assemble(array(), array('name' => 'article_archive')));
        return $site;
    }
    
    protected function buildPages()
    {
        $data = array();
        $pages = $this->api->request('findAllPages');
        foreach($pages['data'] as $page) {
            $data[] = $this->_createSitemapUrl($this->getServerUrl() . $this->router->assemble(array(), 
                       array('name' => $page['slug'])), self::YEARLY);
        }
        return $data;
    }
    
    protected function buildTags()
    {
        $data = array();
        $tags = $this->api->request('findAllTags');
        foreach($tags['data'] as $tag) {
            $data[] = $this->_createSitemapUrl($this->getServerUrl() . $this->router->assemble(array(
                          'slug' => $tag['slug']
                      ), array('name' => 'article_tag')));
        }
        return $data;
    }
    
    protected function buildArticles()
    {
        $data = array();
        // ambil dari archive karena di archive article diambil SEMUA dan dipecah + diurut berdasar tahun.
        $articles = $this->api->request('findArticleArchive');
        foreach($articles['data'] as $archive) {
            foreach($archive['articles'] as $article) {
                $date   = new DateTime($article['createdAt']);
                $data[] = $this->_createSitemapUrl($this->getServerUrl() . $this->router->assemble(array(
                              'year'  => $date->format('Y'),
                              'month' => $date->format('m'),
                              'day'   => $date->format('d'),
                              'slug'  => $article['slug']
                          ), array('name' => 'article_view')), self::YEARLY, '0.9', $date->format('c'));
            }
        }
        return $data;
    }
    
    /**
     * @return \DOMDocument
     */
    protected function getDOMInstance()
    {
        if (! $this->_domInstance) {
            $dom = new DOMDocument('1.0', 'utf-8');
            
            $this->_domInstance = $dom;
        }
        return $this->_domInstance;
    }
    
    /**
     * @param   string $absolute_url
     * @param   string $changefreq frekuensi = 'weekly', 'monthly', 'daily', 'yearly'
     * @param   string $priority url priority
     * @return  \DOMElement
     */
    protected function _createSitemapUrl($absolute_url, $changefreq = self::MONTHLY, $priority = '0.8', $lastmod = false)
    {
        $dom = $this->getDOMInstance();
        $url = $dom->createElement('url');
        
        $loc  = $dom->createElement('loc', $absolute_url);
        $freq = $dom->createElement('changefreq', $changefreq);
        $prio = $dom->createElement('priority', $priority);
        if($lastmod === false) {
            $mod = $dom->createElement('lastmod', date('c'));
        } else {
            $mod = $dom->createElement('lastmod', $lastmod);
        }
        
        $url->appendChild($loc);
        $url->appendChild($mod);
        $url->appendChild($freq);
        $url->appendChild($prio);
        
        return $url;
    }
}
