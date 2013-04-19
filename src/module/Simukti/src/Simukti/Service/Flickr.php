<?php
/**
 * Description of Flickr
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Service;

use Zend\EventManager\Event;
use Zend\Json\Json;
use Zend\Json\Decoder as JsonDecoder;

class Flickr extends HttpParser
{
    const FLICKR_HOST  = 'http://api.flickr.com';
    const FLICKR_PATH  = '/services/rest/';
    
    protected $options;
    protected $defaultParams = array(
        'format'         => 'json',
        'nojsoncallback' => 1
    );
    
    public function setOptions(array $options)
    {
        $this->options = $options;
        $this->defaultParams['user_id'] = $this->getOption('user_id');
        $this->defaultParams['api_key'] = $this->getOption('api_key');
        $this->defaultParams['per_page'] = $this->getOption('fetch_per_page');
    }
    
    public function getOption($key)
    {
        return (isset($this->options[$key])) ? $this->options[$key] : false;
    }
    
    public function getPublicPhotos($page = 1)
    {
        if(!is_int($page)) return false;
        
        $params = $this->defaultParams;
        $params['method']   = 'flickr.people.getPublicPhotos';
        $params['page']     = $page;
        $params['extras']   = 'date_upload, owner_name, url_sq, url_t, url_s, 
                               url_q, url_m, url_n, url_z, url_c, url_l';
        
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
        $client->setUri(self::FLICKR_HOST);
        $client->getUri()->setPath(self::FLICKR_PATH);
        $client->setParameterGet($params);
        
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
            'method'    => $eventParams['method'],
            'username'  => $this->getOption('username')
        );
        if( $event->getParam('page', false) !== false ) {
            $params['page'] = $event->getParam('page');
        }
        if( $event->getParam('per_page', false) !== false ) {
            $params['per_page'] = $event->getParam('per_page');
        }
        $key = $this->_stringNormalizer(implode('_', $params));
        return $key;
    }
}
