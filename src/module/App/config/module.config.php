<?php
return array(
    'view_helpers' => array(),
    'view_manager' => array(
        'display_not_found_reason' => (PRODUCTION === true) ? false : true,
        'display_exceptions'       => (PRODUCTION === true) ? false : true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => realpath(__DIR__ . '/../view/layout/layout.phtml'),
            'error/404'               => realpath(__DIR__ . '/../view/error/404.phtml'),
            'error/index'             => realpath(__DIR__ . '/../view/error/index.phtml')
        ),
        'template_path_stack' => array(
            'app' => realpath(__DIR__ . '/../view')
        )
    )
);
