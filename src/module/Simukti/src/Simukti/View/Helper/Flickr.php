<?php
/**
 * Description of Flickr
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Simukti\Service;

class Flickr extends AbstractHelper
{
    /**
     * @var \Simukti\Service\Flickr
     */
    protected $flickr;
    
    public function setFlickr(Service\Flickr $flickr)
    {
        $this->flickr = $flickr;
    }
    
    /**
     * Proxy to flickr service
     * 
     * @return  \Simukti\Service\Flickr
     */
    public function __invoke()
    {
        return $this->flickr;
    }
}
