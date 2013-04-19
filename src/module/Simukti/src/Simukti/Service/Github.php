<?php
/**
 * Description of Github
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Service;

use Zend\EventManager\Event;
use Zend\Json\Json;
use Zend\Json\Decoder as JsonDecoder;

class Github extends HttpParser
{
    const GITHUB_HOST  = 'https://api.github.com';
    const CACHE_PREFIX = 'github_';
    
    protected $options;
    
    public function setOptions(array $options)
    {
        $this->options = $options;
    }
    
    public function getOption($key)
    {
        return (isset($this->options[$key])) ? $this->options[$key] : false;
    }
    
    public function getRepositories()
    {
        $params = array(
            'path' => '/users/' . $this->getOption('username') . '/repos'
        );
        
        $this->events()->attach(self::API_BEFORE, array($this, 'before'));
        $checkCache = $this->events()->triggerUntil(self::API_BEFORE, $this, $params, function($result) {
            return (is_array($result));
        });
        if($checkCache->stopped()) {
            return $checkCache->last();
        }
        
        $this->events()->attach(self::API_SEND, array($this, 'send'));
        $fetch = $this->events()->trigger(self::API_SEND, $this, $params);
        return $fetch->last();
    }
    
    public function send(Event $event)
    {
        $params = $event->getParams();
        
        $client = $this->getHttpClient();
        $client->setUri(self::GITHUB_HOST);
        $client->getUri()->setPath($params['path']);
        $request = $client->send();
        $result  = JsonDecoder::decode($request->getBody(), Json::TYPE_ARRAY);
        $params[self::RESULT_KEY] = $result;
        
        $this->events()->attach(self::API_AFTER, array($this, 'after'))
                       ->call(array($event->setParams($params)));
        
        return $result;
    }
    
    protected function getCacheKey(Event $event)
    {
        $eventParams = $event->getParams();
        $params = array(
            'username' => $this->getOption('username'),
            'path'     => $eventParams['path'],
        );
        $key = $this->_stringNormalizer(implode('_', $params));
        return self::CACHE_PREFIX.$key;
    }
}
