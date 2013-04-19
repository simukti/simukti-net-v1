<?php
return array(
    'simukti'   => include_once __DIR__ . '/simukti.php',
    'cache'     => include_once __DIR__ . '/module.cache.php',
    'router'    => array(
        'routes'    => include_once __DIR__ . '/module.routes.php'
    ),
    'service_manager' => array(
        'factories' => array(
            'simukti-service-github'      => 'Simukti\Factory\GithubService',
            'simukti-service-flickr'      => 'Simukti\Factory\FlickrService',
            'simukti-service-gist'        => 'Simukti\Factory\GistService',
            'simukti-service-feed'        => 'Simukti\Factory\FeedService',
            'simukti-service-sitemap'     => 'Simukti\Factory\SitemapService',
            'simukti-service-page-routes' => 'Simukti\Factory\PageRoutesService',
            'simukti-service-api'         => 'Simukti\Factory\ApiService'
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Simukti\Controller\Page'    => 'Simukti\Controller\Page',
            'Simukti\Controller\Article' => 'Simukti\Controller\Article'
        ),
        'factories' => array(
            'Simukti\Controller\Async'  => 'Simukti\Factory\AsyncController',
            'Simukti\Controller\Misc'   => 'Simukti\Factory\MiscController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'simukti' => realpath(__DIR__ . '/../view')
        )
    )
);
