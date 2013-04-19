<?php
/**
 * Description of Disqus
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\View\Helper;

use \InvalidArgumentException;
use Zend\View\Helper\AbstractHelper;

class Disqus extends AbstractHelper
{
    protected $shortname;
    protected $development = 0;
    
    public function __construct($options = null)
    {
        if (null !== $options) {
            $this->setOptions($options);
        }
    }
    
    public function setOptions($options)
    {
        if (! is_array($options)) {
            throw new InvalidArgumentException('parameter $options harus array.');
        }
        
        foreach ($options as $key => $value) {
            switch ($key) {
                case 'shortname':
                    $this->shortname = $value;
                    break;
                case 'development':
                    $this->development = $value ? 1 : 0;
                    break;
                default:
                    break;
            }
        }
    }
    
    /**
     * @param   string $identifier  disqus identifier
     * @param   string $url         disqus absolute url
     * @param   string $title       disqus title
     * @param   string $shortname   disqus shortname
     * @param   int $development    disqus developer param
     * @return  string              disqus div id tag dan inline javascript
     * @throws  InvalidArgumentException
     */
    public function __invoke($identifier = '', $url = '', $title = '', $shortname = false)
    {
        if (! $shortname && ! $this->shortname) {
            throw new InvalidArgumentException('Disqus shortname was not provided.');
        } elseif (! $shortname) {
            $shortname = $this->shortname;
        }
        
        $development = $this->development;
        
        if (! preg_match('#^(http|https)://#', $url)) {
            $url = $this->view->serverUrl() . $url;
        }
        $disqusTag    = '<div class="post-comments"><h4>Comment(s)</h4><div id="disqus_thread"></div></div>';
        $disqusScript =<<<EOH
        var disqus_developer  = $development;
        var disqus_shortname  = '$shortname';
        var disqus_title      = '$title';
        var disqus_identifier = '$identifier';
        var disqus_url        = '$url';
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
EOH;
        if( ($this->development == 0) && ($_SERVER['SERVER_NAME'] !== DEV_SERVER_NAME) ) {
            $this->view->inlineScript()->appendScript($disqusScript);
            return $disqusTag;
        } 
    }
}
