<?php
/**
 * Description of Article
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Controller;

use \DateTime;
use Zend\View\Model\ViewModel;

class Article extends BaseController
{
    public function viewAction()
    {
        $params = $this->params()->fromRoute();
        $result = $this->getApi()->request('findOneArticle', $params);
        if(! $result) {
            return $this->notFoundAction();
        }
        $viewModel = new ViewModel(array(
            'result'  =>$result
        ));
        
        return $viewModel;
    }
    
    public function tagAction()
    {
        $result = $this->getApi()->request('findAllArticles', array(
                       'page'          => 1,
                       'post_per_page' => $this->params()->fromRoute('per_page', 200),
                       'tag'           => $this->params()->fromRoute('slug')
                  ));
        if(! $result) {
            return $this->notFoundAction();
        }
        $viewModel = new ViewModel(array(
            // nama lengkap tag yang ditemukan pasti ada di: $result['data'][0] <--- index ke-0
            'tag'    => $result['data'][0]['tag']['name'],
            'result' => $result
        ));
        
        return $viewModel;
    }
    
    public function shortcutAction()
    {
        $result = $this->getApi()->request('findOneArticle', array(
                       'shortcut' => $this->params()->fromRoute('id')
                  ));
        if(! $result) {
            return $this->notFoundAction();
        }
        $date = new DateTime($result['data']['createdAt']);
        
        return $this->redirect()->toRoute('article_view', array(
            'year'  => $date->format('Y'),
            'month' => $date->format('m'),
            'day'   => $date->format('d'),
            'slug'  => $result['data']['slug'],
        ))->setStatusCode(301);
    }
    
    public function archiveAction()
    {
        $result = $this->getApi()->request('findArticleArchive', array());
        if(! $result) {
            return $this->notFoundAction();
        }
        $tags   = $this->getApi()->request('findAllTags', array());
        $viewModel = new ViewModel(array(
            'result' => $result,
            'tags'   => $tags
        ));
        
        return $viewModel;
    }
    
    /**
     * Ini dulu /blog/, tapi sekarang /blog/ akan di-redirect permanen ke /blog/archive/
     */
    public function oldListAction()
    {
        return $this->redirect()
                    ->toRoute('article_archive')
                    ->setStatusCode(301);
    }
}
