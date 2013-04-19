<?php
return array(
    'modules' => array(
        'App',
        'Simukti'
    ),
    'module_listener_options' => array(
        'module_paths' => array(
            'App'       => APPLICATION_PATH . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'App',
            'Simukti'   => APPLICATION_PATH . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'Simukti'
        ),
        'config_cache_enabled'  => PRODUCTION,
        'config_cache_key'      => 'simuktinetwork-configs.cache',
        'cache_dir'             => CACHE_PATH . DIRECTORY_SEPARATOR . 'configs'
    ),
);
