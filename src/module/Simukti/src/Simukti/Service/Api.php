<?php
/**
 * Description of Api
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Service;

use \InvalidArgumentException as InvalidApiRequest;
use Zend\EventManager\Event;
use SimuktiNetwork\Api as SimuktiApi;

class Api extends BaseService
{
    /**
     * @var SimuktiApi
     */
    protected $api;
    
    /**
     * @param   \SimuktiNetwork\Api $api
     */
    public function __construct(SimuktiApi $api)
    {
        $this->api = $api;
    }
    
    /**
     * Proxy ke \SimuktiNetwork\Api, dengan parameter $cacheRequest, jika $cacheRequest = true, 
     * maka semua hasil request api akan di-cache-kan. Silahkan check Simukti\Factory\ApiService.
     * 
     * @param   string      $sdkMethod
     * @param   array       $params
     * @param   boolean     $cacheRequest Default adalah boolean "false".
     * @return  false|mixed
     * @throws  InvalidApiRequest jika nama method yang diberikan pada $sdkMethod tidak ada di \SimuktiNetwork\Api
     */
    public function request($sdkMethod, array $params = array(), $cacheRequest = PRODUCTION)
    {
        if(! method_exists($this->api, $sdkMethod)) {
            throw new InvalidApiRequest(sprintf("'%s' is not available in siMukti PHP-SDK.", $sdkMethod));
        }
        
        // $params juga akan digunakan sebagai $key cache, jadi nama controller dan action tidak diperlukan.
        if(isset($params['controller'])) {
            unset($params['controller']);
        } 
        if(isset($params['action'])) {
            unset($params['action']);
        }
        
        $params['sdkMethod']    = $sdkMethod;
        $params['cacheRequest'] = $cacheRequest;
        
        if($cacheRequest) {
            $this->events()->attach(self::API_BEFORE, array($this, 'before'));
            $checkCache = $this->events()->triggerUntil(self::API_BEFORE, $this, $params, function($result) {
                // HASIL RETURN DISINI AKAN SANGAT MENENTUKAN PERFORMA APLIKASI.
                // Mohon diperhatikan, return jangan sampai terbalik :
                // jika di SimuktiNetwork\Api semua hasil $request->getBody() di decode menggunakan Json::TYPE_ARRAY
                // maka disini menggunakan "return (is_array($result));".
                // Tetapi jika $request->getBody() di decode menggunakan JsonDecoder::decode secara default (Json::TYPE_OBJECT),
                // maka disini menggunakan "return ($result instanceOf \stdClass);"
                return (is_array($result));
            });
            
            // Jika ada di-cache, segera kembalikan hasilnya, method send() dan after() tidak akan pernah dipanggil.
            if($checkCache->stopped()) {
                return $checkCache->last();
            }
        }
        
        // baris ini tidak pernah akan di-execute jika $cacheRequest = true DAN hasil request sudah ada di cache.
        $this->events()->attach(self::API_SEND, array($this, 'send'));
        $fetch = $this->events()->trigger(self::API_SEND, $this, $params);
        return $fetch->last();
    }
    
    /**
     * @param   \Zend\EventManager\Event $event
     * @return  false|mixed
     */
    public function before(Event $event) 
    {
        $params       = $event->getParams();
        $sdkMethod    = $params['sdkMethod'];
        $cacheRequest = $params['cacheRequest'];
        unset($params['sdkMethod']);
        unset($params['cacheRequest']);
        
        if(! $cacheRequest) {
            return false;
        }
        
        $cache = $this->getCache();
        // $key disini harus sama dengan $key di method $this->after();
        // Jika tidak, semua hasil api request tidak akan di-cache.
        $key = (count($params)) ? $sdkMethod.'_'.implode('_', $params) : $sdkMethod;
        
        if(! $cache->hasItem($key)) {
            return false;
        }
        return $cache->getItem($key);
    }
    
    /**
     * @param   \Zend\EventManager\Event $event
     * @return  array
     */
    public function send(Event $event) 
    {
        $params        = $event->getParams();
        $sdkMethod     = $params['sdkMethod'];
        $cacheRequest  = $params['cacheRequest'];
        $requestParams = $params;
        unset($requestParams['sdkMethod']);
        unset($requestParams['cacheRequest']);
        
        // request get ke api server dilakukan disini.
        $result = $this->api->{$sdkMethod}($requestParams);
        $params[self::RESULT_KEY] = $result;
        
        if($cacheRequest) {
            $this->events()->attach(self::API_AFTER, array($this, 'after'))
                           ->call(array($event->setParams($params)));
        }
        return $result;
    }
    
    /**
     * @param   \Zend\EventManager\Event $event
     */
    public function after(Event $event) 
    {
        $params     = $event->getParams();
        $cache      = $this->getCache();
        $sdkMethod  = $params['sdkMethod'];
        $result     = $params[self::RESULT_KEY];
        unset($params['cacheRequest']);
        unset($params['sdkMethod']);
        unset($params[self::RESULT_KEY]);
        
        // $key disini harus sama dengan $key di method $this->before();
        // Jika tidak, semua hasil api request tidak akan di-cache.
        $key = (count($params)) ? $sdkMethod.'_'.implode('_', $params) : $sdkMethod;
        
        $cache->setItem($key, $result);
    }
    
}
