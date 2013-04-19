<?php
/**
 * Description of ArticleRecent
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\View\Helper;

use \RuntimeException;
use Zend\View\Helper\AbstractHelper;
use Simukti\Service\Api;

/**
 * View helper ini untuk mengambil artikel terbaru, jika ingin yang via XHR, silahkan cek Simukti\Controller\Async
 */
class ArticleRecent extends AbstractHelper
{
    protected $api;
    protected $apiMethod = 'findAllArticles';
    protected $params    = array(
                              'page'      => 1,
                              'per_page'  => 8
                           );
    protected $templateName = 'simukti/article/_list';
    
    public function setApi(Api $api)
    {
        $this->api = $api;
    }
    
    /**
     * @return  \Simukti\Service\Api
     * @throws  RuntimeException
     */
    public function getApi()
    {
        if(! $this->api || ! $this->api instanceof Api) {
            throw new RuntimeException('Api service was not set.');
        }
        return $this->api;
    }
    
    public function __invoke(array $params = array())
    {
        if(! count($params)) {
            $params = $this->params;
        }
        $articles = $this->getApi()->request($this->apiMethod, $params);
        if(! $articles) return;
        return $this->view->render($this->templateName, array(
            'title'     => 'Recent Articles',
            'articles'  => $articles['data']
        ));
    }
}
