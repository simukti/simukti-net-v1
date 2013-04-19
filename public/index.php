<?php
/**
 * Request flow dalam gambaran singkat, [web_browser] <---> .htaccess (index.php) <---> [www.simukti.net]
 *
 * @author Sarjono Mukti Aji <me@simukti.net>
 */
 
// ketika PRODUCTION = true, jangan kaget jika semuanya akan menjadi sangat sangat kencang !! :D
defined('PRODUCTION')        || define('PRODUCTION', false);
defined('DEV_SERVER_NAME')   || define('DEV_SERVER_NAME', 'http://simukti-net.client.dev');
defined('SIMUKTI_NET')       || define('SIMUKTI_NET', 'http://www.simukti.net'); // online address
defined('GLOBAL_LIBRARY')    || define('GLOBAL_LIBRARY', '/WebApplications/_PHPFrameworks');
defined('APPLICATION_PATH')  || define('APPLICATION_PATH', __DIR__ . '/../src');

require_once APPLICATION_PATH . DIRECTORY_SEPARATOR . 'init' . DIRECTORY_SEPARATOR . 'autoloader.php';
