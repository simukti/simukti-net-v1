<?php
/**
 * Description of Livefyre
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\View\Helper;

use \InvalidArgumentException;
use Zend\View\Helper\AbstractHelper;

class Livefyre extends AbstractHelper
{
    protected $siteId;
    protected $development = 0;
    protected $js;
    
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
                case 'siteId':
                    $this->siteId = $value;
                    break;
                case 'js':
                    $this->js = $value;
                    break;
                case 'development':
                    $this->development = $value ? 1 : 0;
                    break;
                default:
                    break;
            }
        }
    }
    
    public function __invoke($url = '', $title = '', $siteId = false)
    {
        if (! $siteId && ! $this->siteId) {
            throw new InvalidArgumentException('Livefyre siteId param was not found.');
        } elseif (! $siteId) {
            $siteId = $this->siteId;
        }
        
        $development = $this->development;
        
        if (! preg_match('#^(http|https)://#', $url)) {
            $url = $this->view->serverUrl() . $url;
        }
        $livefyreTag    = '<div class="content-comment"><div id="livefyre-comments"></div></div>';
        $livefyreScript =<<<EOH
        (function () {
            fyre.conv.load({}, [{
                el: 'livefyre-comments',
                network: "simukti.net",
                siteId: "$siteId",
                articleId: '$title',
                signed: true,
                collectionMeta: {
                    articleId: '$title',
                    url: '$url',
                }
            }], function() {});
        }());
EOH;
        if( ($this->development == 0) && ($_SERVER['SERVER_NAME'] == SIMUKTI_NET) ) {
            $this->view->headScript()->appendFile($this->js);
            $this->view->inlineScript()->appendScript($livefyreScript);
            return $livefyreTag;
        } 
        
        $this->view->headScript()->appendFile($this->js);
            $this->view->inlineScript()->appendScript($livefyreScript);
            return $livefyreTag;
    }
}