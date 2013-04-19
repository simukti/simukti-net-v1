<?php
/**
 * Description of AbstractApi
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace SimuktiNetwork;

use \DateTime;
use \InvalidArgumentException;
use Zend\Http\Client as HttpClient;

abstract class AbstractApi
{
    const REQUEST_RESPONSE_TYPE = 'application/json';
    
    const TOKEN_NAME = 'SMKTX';
    const UA_NAME    = 'SIMUKTINETWORK';
    
    const GET        = 'GET';
    const POST       = 'POST';
    const PUT        = 'PUT';
    const DELETE     = 'DELETE';
    
    /**
     * Api configuration
     * 
     * @var array
     */
    protected $_configs;
    
    /**
     * Http client to be used for api request.
     * 
     * @var HttpClient
     */
    protected $_httpClient;
    
    /**
     * @param   array $configs
     */
    public function __construct(array $configs = array()) 
    {
        if(! empty($configs)) {
            $this->setConfigs($configs);
        }
    }
    
    /**
     * Required configuration array are : server_url, client_key, client_secret
     * 
     * @param   array $configs
     */
    public function setConfigs(array $configs) 
    {
        $required = array('server_url', 'client_key', 'client_secret');
        $validConfigs = $this->_validateRequiredParams($required, $configs);
        if(!$validConfigs) {
            return false;
        }
        $this->_configs = $configs;
    }
    
    /**
     * @return  array
     * @throws  \InvalidArgumentException
     */
    public function getConfigs() 
    {
        if((null === $this->_configs) || (! is_array($this->_configs))) {
            throw new InvalidArgumentException('Api configs was not provided.');
        }
        return $this->_configs;
    }
    
    /**
     * @param HttpClient $httpClient
     */
    public function setHttpClient(HttpClient $httpClient = null)
    {
        $this->_httpClient = $httpClient;
    }
    
    /**
     * Get current http client or if it was not set, give them default \Zend\Http\Client instance
     * 
     * @return HttpClient
     */
    public function getHttpClient() 
    {
        if( (! $this->_httpClient) || (! $this->_httpClient instanceof HttpClient) ) {
            $this->_httpClient = new HttpClient();
        }
        return $this->_httpClient;
    }
    
    /**
     * @param   array $required
     * @param   array $provided
     * @throws  \InvalidArgumentException
     */
    protected function _validateRequiredParams(array $required, array $provided) 
    {
        foreach($required as $key) {
            if(! array_key_exists($key, $provided)) {
                /*throw new \InvalidArgumentException(
                    sprintf("I need '%s' parameter, but it was not found.", $key)
                ); */
                return false;
            }
        }
        return true;
    }
    
    /**
     * @param   string $secret
     * @return  string Encrypted data 
     */
    protected function _generateRequestToken($secret) 
    {
        $timestamp   = new DateTime('now');
        $userStamp   = strtotime($timestamp->format(\DateTime::ATOM));
        $base64stamp = base64_encode($userStamp);
        $clientCode  = self::TOKEN_NAME . ':' . $base64stamp;
        $signedData  = hash_hmac("sha256", $clientCode, $secret);
        return $clientCode . ':' . $signedData;
    }
    
    /**
     * Setup http client with required request header and method.
     * 
     * @param   string $path
     * @param   string $method
     * @return  HttpClient
     */
    protected function _setupClient($path, $method) 
    {
        $httpClient = $this->getHttpClient();
        $configs    = $this->getConfigs();
        
        $httpClient->resetParameters(true)->setUri($configs['server_url'])
                   ->setMethod($method)
                   ->setHeaders(array(
                       'accept'         => self::REQUEST_RESPONSE_TYPE,
                       'user-agent'     => self::UA_NAME,
                       'x-client-id'    => $configs['client_key'],
                       'x-client-token' => $this->_generateRequestToken($configs['client_secret'])
                   ));
        $uri = $httpClient->getUri();
        if ($path[0] != '/' && $uri[strlen($uri) - 1] != '/') {
            $path = '/' . $path;
        }
        $uri->setPath($path);
        return $httpClient;
    }
    
    /**
     * @param   string $path
     * @param   array $params
     * @return  \Zend\Http\Response
     */
    protected function _get($path, array $params)
    {
        $client = $this->_setupClient($path, self::GET);
        $client->setParameterGet($params);
        return $client->send();
    }
}
