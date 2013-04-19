<?php
/**
 * Description of GistParser
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\View\Helper;

use \RuntimeException;
use Zend\View\Helper\AbstractHelper;
use Simukti\Service\Gist;

/**
 * Proxy ke \Simukti\Service\Gist::filter(), terpisah dari proses action controller.
 * Setelah Controller memberikan response dan me-render view model, ini baru dipanggil.
 */
class GistParser extends AbstractHelper
{
    protected $gist;
    
    public function setService(Gist $gist)
    {
        $this->gist = $gist;
    }
    
    /**
     * @return  \Simukti\Service\Gist
     * @throws  RuntimeException
     */
    public function getService()
    {
        if(! $this->gist || ! $this->gist instanceof Gist) {
            throw new RuntimeException('Gist service belum di-set.');
        }
        return $this->gist;
    }
    
    /**
     * Proxy ke \Simukti\Service\Gist::filter()
     * 
     * @param   string $content
     * @return  string
     */
    public function __invoke($content)
    {
        return $this->getService()->parse($content);
    }
}
