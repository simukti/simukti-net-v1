<?php
/**
 * Description of Page
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\Controller;

use Zend\View\Model\ViewModel;

class Page extends BaseController
{
    public function indexAction()
    {
        $tags       = $this->getApi()->request('findAllTags');
        $viewModel  = new ViewModel(array(
            'tags' => $tags
        ));
        
        return $viewModel;
    }
    
    /**
     * "dynamic action" ;)
     * Ini dipanggil sebagai Literal-route dari \Simukti\Service\PageRoutes::getPageRoutes()
     * "kunci"-nya adalah slug dari tiap page
     */
    public function viewAction()
    {
        $params = array(
            'slug' => $this->params()->fromRoute('slug')
        );
        $result = $this->getApi()->request('findOnePage', $params);
        if(! $result) {
            return $this->notFoundAction();
        }
        $viewModel = new ViewModel(array(
            'result'  => $result
        ));
        
        return $viewModel;
    }
}
