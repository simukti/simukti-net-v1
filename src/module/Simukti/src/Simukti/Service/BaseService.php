<?php
/**
 * Description of BaseService
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Service;

use Zend\Cache\Storage\StorageInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\Event;

abstract class BaseService
{
    const RESULT_KEY = '__EVENT_RESULT__';
    
    const API_BEFORE = 'before.new.request';
    const API_SEND   = 'send.new.request';
    const API_AFTER  = 'after.new.request';

    /**
     * @var \Zend\Cache\Storage\Adapter\Filesystem
     */
    protected $cache;
    
    /**
     * @var EventManager
     */
    protected $events;
    
    /**
     * @param   \Zend\Cache\Storage\StorageInterface $cache
     */
    public function setCache(StorageInterface $cache)
    {
        $this->cache = $cache;
    }
    
    /**
     * @return  \Zend\Cache\Storage\Adapter\Filesystem
     */
    public function getCache()
    {
        return $this->cache;
    }
    
    /**
     * Pembersihan cache ini berdasarkan perintah di query url yang dideskripsikan di \Simukti\Module::flushCache()
     *
     * @param   string $namespace
     * @throws  \Zend\Cache\Exception\RuntimeException jika penghapusan file cache = failed.
     */
    public function cleanCache($namespace)
    {
        $cache = $this->getCache();
        $cache->clearByNamespace($namespace);
    }
    
    /**
     * @param   \Zend\EventManager\EventManager $events
     * @return  \Zend\EventManager\EventManager
     */
    public function events(EventManager $events = null)
    {
        if(null !== $events) {
            $this->events = $events;
        } elseif(null === $this->events) {
            $this->events = new EventManager(__CLASS__);
        }
        return $this->events;
    }
    
    /**
     * generic method untuk event API_BEFORE
     *
     * @param   \Zend\EventManager\Event $event
     * @return  false|mixed
     */
    public function before(Event $event)
    {
        $cache     = $this->getCache();
        $cache_key = $this->getCacheKey($event);
        if(! $cache->hasItem($cache_key)) {
            return false;
        }
        return $cache->getItem($cache_key);
    }
    
    /**
     * generic method untuk event API_AFTER
     *
     * @param   \Zend\EventManager\Event $event
     */
    public function after(Event $event)
    {
        $result = $event->getParam(self::RESULT_KEY);
        $cache  = $this->getCache();
        $cache_key = $this->getCacheKey($event);
        $cache->setItem($cache_key, $result);
    }
    
    /**
     * Untuk menormalkan string cache key
     *
     * @param   string $string
     * @param   string $separator
     * @return  string
     */
    protected function _stringNormalizer($string, $separator = '_')
    {
        $string = iconv('utf-8', 'ascii//translit', $string);
        $string = strtolower($string);
        $string = preg_replace('#[^a-z0-9\-_]#', $separator, $string);
        $string = preg_replace('#-{2,}#', $separator, $string);
        $string = trim($string, $separator);

        return $string;
    }
}
