<?php
/**
 * Description of GoogleAnalytics
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\View\Helper;

use \RuntimeException;
use Zend\View\Helper\AbstractHelper;

class GoogleAnalytics extends AbstractHelper
{
    protected $development;
    protected $key;
    
    public function setOptions(array $options)
    {
        $this->key = (isset($options['account'])) ? $options['account'] : false;
        $this->development = (isset($options['development'])) ? $options['development']: false;
    }
    
    public function __invoke()
    {
        if(! $this->key || ! is_string($this->key)) {
            throw new RuntimeException('Analytic key was not set.');
        }
        $uaKey = $this->key;
        
        $analyticScript =<<<EOH
<script type="text/javascript">
    window.scrType  = 'text/javascript';
    window.scrAsync = 'true';
    window.scrPos   = document.getElementsByTagName('script')[0];
    window.gaLoc    = '//www.google-analytics.com/ga.js';
    var _gaq        = _gaq || []; _gaq.push(['_setAccount', '$uaKey']); _gaq.push(['_trackPageview']);
    (function() {
        var ga = document.createElement('script'); ga.type = scrType; ga.async = scrAsync; ga.src = gaLoc; var s = scrPos; s.parentNode.insertBefore(ga, s);
    })();
</script>
EOH;
        if(! $this->development && ($_SERVER['SERVER_NAME'] !== DEV_SERVER_NAME)) {
            return $analyticScript;
        }
    }
}
