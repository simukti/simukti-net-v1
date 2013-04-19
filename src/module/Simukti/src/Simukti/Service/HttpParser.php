<?php
/**
 * Description of HttpParser
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Service;

use Zend\Http\Client as HttpClient;
use Zend\Http\Client\Adapter\Socket as SocketAdapter;

abstract class HttpParser extends BaseService
{
    /**
     * @var HttpClient
     */
    protected $httpClient;
    
    protected $socketConfigs = array(
        'persistent'            => false,
        'ssltransport'          => 'ssl',
        'sslcert'               => null,
        'sslpassphrase'         => null,
        'sslverifypeer'         => false,
        'sslcapath'             => null,
        'sslallowselfsigned'    => false,
        'sslusecontext'         => false
    );
    
    public function setSocketConfigs(array $configs)
    {
        $this->socketConfigs = $configs;
    }
    
    public function getSocketConfigs()
    {
        return $this->socketConfigs;
    }
    
    /**
     * @param   \Zend\Http\Client $client
     */
    public function setHttpClient(HttpClient $client)
    {
        $this->httpClient = $client;
        return $this;
    }
    
    /**
     * @return  HttpClient
     */
    public function getHttpClient()
    {
        if(! $this->httpClient) {
            $client  = new HttpClient;
            $adapter = new SocketAdapter;
            $adapter->setOptions($this->getSocketConfigs());
            $client->setAdapter($adapter);
            $this->setHttpClient($client);
        }
        return $this->httpClient;
    }
}
