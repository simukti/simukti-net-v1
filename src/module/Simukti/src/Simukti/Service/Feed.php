<?php
/**
 * Description of Feed
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Service;

use \DateTime;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\View\HelperPluginManager;
use Zend\Feed\Writer\Feed as FeedWriter;

class Feed extends BaseService
{
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
     * @return  string XML
     */
    public function getRssFeed()
    {
        $feed = $this->buildRss();
        return $feed;
    }
    
    public function getRssFeedEndpoint()
    {
        $rssUrl = $this->router->assemble(array(), array('name' => 'misc-rss'));
        $endpoint = $this->getServerUrl() . $rssUrl;
        return $endpoint;
    }
    
    public function buildRss()
    {
        $feedWriter = new FeedWriter;
        $feedWriter->setTitle('siMukti.NET Articles');
        $feedWriter->setDescription('Sarjono Mukti Aji\'s Article Feeds');
        $feedWriter->setLink($this->getServerUrl());
        $feedWriter->setFeedLink($this->getRssFeedEndpoint(), 'rss');
        
        $articles = $this->getRecentArticles();
        if($articles) {
            foreach($articles as $article) {
                $date = new DateTime($article['createdAt']);
                $link = $this->getServerUrl() . $this->router->assemble(array(
                              'year'  => $date->format('Y'),
                              'month' => $date->format('m'),
                              'day'   => $date->format('d'),
                              'slug'  => $article['slug']
                          ), array('name' => 'article_view'));
                
                $entry = $feedWriter->createEntry();
                $entry->setTitle($article['title']);
                $entry->setLink($link);
                $entry->setDateCreated($date);
                $feedWriter->addEntry($entry);
            }
        }
        
        $result = $feedWriter->export('rss');
        return $result;
    }
    
    public function getRecentArticles()
    {
        $articles = $this->api->request('findAllArticles', array(
                              'page'      => 1,
                              'per_page'  => 8
                           ));
        
        if(! $articles) {
            return false;
        }
        return $articles['data'];
    }
}
