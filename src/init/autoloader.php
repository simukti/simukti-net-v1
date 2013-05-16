<?php
define('CONFIG_PATH', APPLICATION_PATH . DIRECTORY_SEPARATOR . 'config');

define('CACHE_PATH',  APPLICATION_PATH . DIRECTORY_SEPARATOR . 'data' 
                                       . DIRECTORY_SEPARATOR . 'cache');

/**
 * Tidak perlu include_path, karena ini ngambil absolut file ClassMapAutoloader, 
 * lalu memberitahukan dimana letak file yang akan di autoload via autoload_classmap.php
 */
require_once GLOBAL_LIBRARY . DIRECTORY_SEPARATOR . 'zf22'
                            . DIRECTORY_SEPARATOR . 'Zend' . DIRECTORY_SEPARATOR . 'Loader' 
                            . DIRECTORY_SEPARATOR . 'ClassMapAutoloader.php';

use Zend\Loader\ClassMapAutoloader;
use Zend\Mvc\Application;

$loader = new ClassMapAutoloader(array(
    GLOBAL_LIBRARY   . DIRECTORY_SEPARATOR . 'zf22'
                     . DIRECTORY_SEPARATOR . 'autoload_classmap.php',
	
    APPLICATION_PATH . DIRECTORY_SEPARATOR . 'sdk'
                     . DIRECTORY_SEPARATOR . 'simukti' 
                     . DIRECTORY_SEPARATOR . 'autoload_classmap.php'
));
$loader->register();

if(php_sapi_name() !== 'cli') {
    Application::init(include_once CONFIG_PATH . DIRECTORY_SEPARATOR . 'application.config.php')->run();
}
