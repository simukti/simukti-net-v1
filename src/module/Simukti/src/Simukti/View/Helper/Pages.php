<?php
/**
 * Description of Pages
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Pages extends AbstractHelper
{
    protected $pages;
    protected $iconMap;
    
    public function __invoke()
    {
        return $this;
    }
    
    /**
     * data icon-map ini diambil dari file __MODULE__/config/simukti.php
     * 
     * @param   array $map
     * @return  \Simukti\View\Helper\Pages
     */
    public function setIconMaps(array $map)
    {
        $this->iconMap = $map;
        return $this;
    }
    
    public function getIconMaps()
    {
        return $this->iconMap;
    }
    
    public function setPages(array $pages)
    {
        $this->pages = $pages;
        return $this;
    }
    
    public function getPages()
    {
        return $this->pages;
    }
}
