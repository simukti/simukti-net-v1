<?php
/**
 * Description of BaseController
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Simukti\Service\Api;

abstract class BaseController extends AbstractActionController
{
    /**
     * @var \Simukti\Service\Api
     */
    protected $api;
    
    /**
     * @return  \Simukti\Service\Api
     */
    public function getApi()
    {
        if(! $this->api || ! $this->api instanceof Api) {
            $this->api = $this->getServiceLocator()->get('simukti-service-api');
        }
        return $this->api;
    }
    
    /**
     * Ini untuk code-completion
     *
     * @return  \Zend\Http\Request
     */
    public function getRequest()
    {
        return parent::getRequest();
    }
    
    /**
     * Ini untuk code-completion
     *
     * @return  \Zend\Http\Response
     */
    public function getResponse()
    {
        return parent::getResponse();
    }
}
