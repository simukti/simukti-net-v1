<?php
return array(
    'request' => array(
        'adapter' => array(
            'name' => 'filesystem',
            'options' => array(
                'cache_dir'         => realpath(CACHE_PATH . DIRECTORY_SEPARATOR . 'api'),
                'dir_permission'    => 0755,
                'file_locking'      => 0755,
                'file_permission'   => 0666,
                'dir_level'         => 0,
                'namespace'         => 'api_request',
                'ttl'               => 2592000 // 30 days
            )
        ),
        'plugins' => array(
            'exception_handler' => array(
                'throw_exceptions' => false,
            ),
            'serializer'
        ),
    ),
    'gist' => array(
        'adapter' => array(
            'name' => 'filesystem',
            'options' => array(
                'cache_dir'         => realpath(CACHE_PATH . DIRECTORY_SEPARATOR . 'gist'),
                'dir_permission'    => 0755,
                'file_locking'      => 0755,
                'file_permission'   => 0666,
                'dir_level'         => 0,
                'namespace'         => '_gist',
                'ttl'               => 31536000 // 1 years
            )
        ),
        'plugins' => array(
            'exception_handler' => array(
                'throw_exceptions' => false,
            ),
            'serializer'
        ),
    ),
    'rest' => array(
        'adapter' => array(
            'name' => 'filesystem',
            'options' => array(
                'cache_dir'         => realpath(CACHE_PATH . DIRECTORY_SEPARATOR . 'rest'),
                'dir_permission'    => 0755,
                'file_locking'      => 0755,
                'file_permission'   => 0666,
                'dir_level'         => 0,
                'namespace'         => 'rest',
                'ttl'               => 31536000 // 1 years
            )
        ),
        'plugins' => array(
            'exception_handler' => array(
                'throw_exceptions' => false,
            ),
            'serializer'
        ),
    )
);