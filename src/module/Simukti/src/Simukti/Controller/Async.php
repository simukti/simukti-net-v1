<?php
/**
 * Description of Async
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Controller;

use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Simukti\Service\Github;
use Simukti\Service\Flickr;

/**
 * Controller ini hanya digunakan via XHR
 */
class Async extends BaseController
{
    /**
     * Jika RESULT_KEY diganti, maka semua data key di javascript / view script
     * yang memanggil controller ini, harus diganti juga.
     */
    const RESULT_KEY = '__SIMUKTIDOTNET__';
    
    protected $defaultHeaders = array(
        'content-type'  => 'application/json',
        'cache-control' => 'public',
        'pragma'        => 'public',
        'connection'    => 'close'
    );
    
    /**
     * @var \Simukti\Service\Github
     */
    protected $github;
    
    /**
     * @var \Simukti\Service\Flickr
     */
    protected $flickr;
    
    public function setGithubService(Github $github)
    {
        $this->github = $github;
    }
    
    public function setFlickrService(Flickr $flickr)
    {
        $this->flickr = $flickr;
    }
    
    public function articlesAction()
    {
        $response  = $this->getResponse();
        $viewModel = new JsonModel();
        $params    = array('page'     => 1,
                           'per_page' => 8);
        $articles  = $this->getApi()->request('findAllArticles', $params);
        if(! $articles) {
            $response->setStatusCode(404);
            $viewModel->setVariable(self::RESULT_KEY, 'not found');
            $response->setContent($viewModel->serialize());
            return $response;
        }
        $viewModel->setVariable(self::RESULT_KEY, $articles);
        $response->setContent($viewModel->serialize());
        return $response;
    }
    
    public function githubAction()
    {
        $response     = $this->getResponse();
        $viewModel    = new JsonModel();
        $repositories = $this->github->getRepositories();
        
        if(! $repositories) {
            $response->setStatusCode(404);
            $viewModel->setVariable(self::RESULT_KEY, 'not found');
            $response->setContent($viewModel->serialize());
            return $response;
        }
        
        $viewModel->setVariable(self::RESULT_KEY, $repositories);
        $response->setContent($viewModel->serialize());
        return $response;
    }
    
    public function flickrAction()
    {
        $response  = $this->getResponse();
        $viewModel = new JsonModel();
        $photos    = $this->flickr->getPublicPhotos();
        
        if(! $photos) {
            $response->setStatusCode(404);
            $viewModel->setVariable(self::RESULT_KEY, 'not found');
            $response->setContent($viewModel->serialize());
            return $response;
        }
        
        $viewModel->setVariable(self::RESULT_KEY, $photos);
        $response->setContent($viewModel->serialize());
        return $response;
    }
    
    public function onDispatch(MvcEvent $event)
    {
        if(! $this->getRequest()->isXmlHttpRequest()) {
            return $this->redirect()->toRoute('home');
        } 
        //$this->defaultHeaders['expires'] = gmdate('D, d M Y H:i:s', time() + 600) . ' GMT';
        $response = $this->getResponse();
        $headers  = $response->getHeaders()->clearHeaders();
        $headers->addHeaders($this->defaultHeaders);
        
        parent::onDispatch($event);
    }
}
