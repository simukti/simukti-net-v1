<?php
/**
 * Description of Misc
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Controller;

use Zend\Mvc\MvcEvent;
use Simukti\Service\Sitemap;
use Simukti\Service\Feed;

class Misc extends BaseController
{
    protected $defaultHeaders = array(
        'content-type'  => 'application/xml',
        'cache-control' => 'public',
        'pragma'        => 'public',
        'connection'    => 'close'
    );
    
    /**
     * @var \Simukti\Service\Sitemap
     */
    protected $sitemap;
    
    /**
     * @var \Simukti\Service\Feed
     */
    protected $feed;
    
    public function setSitemapService(Sitemap $sitemap)
    {
        $this->sitemap = $sitemap;
    }
    
    public function setFeedService(Feed $feed)
    {
        $this->feed = $feed;
    }
    
    public function sitemapAction()
    {
        $sitemap  = $this->sitemap->getSitemap();
        $response = $this->getResponse();
        $response->setContent($sitemap);
        return $response;
    }
    
    public function rssAction()
    {
        $rss = $this->feed->getRssFeed();
        $response = $this->getResponse();
        $response->setContent($rss);
        return $response;
    }
    
    public function onDispatch(MvcEvent $e)
    {
        //$this->defaultHeaders['expires'] = gmdate('D, d M Y H:i:s', time() + 432000 /*5 hari*/ ) . ' GMT';
        $response = $this->getResponse();
        $headers  = $response->getHeaders()->clearHeaders();
        $headers->addHeaders($this->defaultHeaders);
        
        parent::onDispatch($e);
    }
}
