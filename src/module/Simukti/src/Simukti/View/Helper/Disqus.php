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
        $disqusTag    = '<button class="btn btn-success btn-sm pull-right" id="comment-button" onclick=reset();>
                         <i class="icon-comment"></i> Show Comments</button>
                         <div class="post-comments"><div id="disqus_thread"></div></div>';
        $disqusScript =<<<EOH
        // from http://www.paulund.co.uk/ajax-disqus-comment-system
        
        var reset = function(){
            DISQUS.reset({
              reload: true,
              config: function () {
                this.page.identifier = '$identifier';
                this.page.url = '$url';
                this.page.title = '$title';
              }
            });
        };
        
        \$j = jQuery.noConflict();
        \$j(document).ready(function() {
            \$j('#comment-button').on('click', function() {
                \$j(this).remove();
                var disqus_developer  = $development;
                var disqus_shortname  = '$shortname';
                var disqus_title      = '$title';
                var disqus_identifier = '$identifier';
                var disqus_url        = '$url';

                \$j.ajax({
                     type: 'GET',
                     url: '//' + disqus_shortname + '.disqus.com/embed.js',
                     dataType: 'script',
                     cache: true
                 });
            });
        });
EOH;
        if( ($this->development == 0) && ($_SERVER['SERVER_NAME'] !== DEV_SERVER_NAME) ) {
            $this->view->inlineScript()->appendScript($disqusScript);
            return $disqusTag;
        } 
    }
}
