<?php
return array(
    /* PAGE */
    'home' => array(
        'type' => 'Literal',
        'options' => array(
            'route'    => '/',
            'defaults' => array(
                'controller' => 'Simukti\Controller\Page',
                'action'     => 'index',
            ),
        ),
        'may_terminate' => true,
    ),
    
    /* ARTICLE / BLOG */
    'article_tag' => array(
        'type' => 'Regex',
        'options' => array(
            'regex'    => '/blog/tag/(?<slug>[a-z0-9\-_]{2,48})/',
            'spec'     => '/blog/tag/%slug%/',
            'defaults' => array(
                'controller' => 'Simukti\Controller\Article',
                'action'     => 'tag',
            ),
        ),
        'may_terminate' => true,
    ),
    /* karena url di blog lama masih ada pattern "/blog/category/*" */
    'article_category' => array(
        'type' => 'Regex',
        'options' => array(
            'regex'    => '/blog/category/(?<slug>[a-z0-9\-_]{2,48})/',
            'spec'     => '/blog/category/%slug%/',
            'defaults' => array(
                'controller' => 'Simukti\Controller\Article',
                'action'     => 'tag',
            ),
        ),
        'may_terminate' => true,
    ),
    'article_view' => array(
        'type' => 'Regex',
        'options' => array(
            'regex'    => '/blog/(?<year>20[0-9]{2})/(?<month>[1-9]|0[1-9]|1[012])/(?<day>[1-9]|0[1-9]|[12][0-9]|3[01])/(?<slug>[a-zA-Z0-9\-_]+)(/| )',
            'spec'     => '/blog/%year%/%month%/%day%/%slug%/',
            'defaults' => array(
                'controller' => 'Simukti\Controller\Article',
                'action'     => 'view',
            ),
        ),
        'may_terminate' => true,
    ),
    'article_shortcut' => array(
        'type' => 'Regex',
        'options' => array(
            'regex'    => '/(?<id>A[0-9]{10})',
            'spec'     => '/%id%',
            'defaults' => array(
                'controller' => 'Simukti\Controller\Article',
                'action'     => 'shortcut',
            ),
        ),
        'may_terminate' => true,
    ),
    'article_archive' => array(
        'type' => 'Literal',
        'options' => array(
            'route'    => '/blog/archives/',
            'defaults' => array(
                'controller' => 'Simukti\Controller\Article',
                'action'     => 'archive',
            ),
        ),
        'may_terminate' => true,
    ),
    // di versi yang sekarang, /blog/ akan diarahkan permanen ke /blog/archive/
    'article_list' => array(
        'type' => 'Literal',
        'options' => array(
            'route'    => '/blog/',
            'defaults' => array(
                'controller' => 'Simukti\Controller\Article',
                'action'     => 'oldList',
            ),
        ),
        'may_terminate' => true,
    ),
    
    /* ASYNC/XHR */
    'async-flickr' => array(
        'type' => 'Literal',
        'options' => array(
            'route'    => '/async/flickr',
            'defaults' => array(
                'controller'  => 'Simukti\Controller\Async',
                'action'      => 'flickr',
            ),
        ),
        'may_terminate' => true,
    ),
    'async-github' => array(
        'type' => 'Literal',
        'options' => array(
            'route'    => '/async/github',
            'defaults' => array(
                'controller'  => 'Simukti\Controller\Async',
                'action'      => 'github',
            ),
        ),
        'may_terminate' => true,
    ),
    'async-articles' => array(
        'type' => 'Literal',
        'options' => array(
            'route'    => '/async/articles',
            'defaults' => array(
                'controller'  => 'Simukti\Controller\Async',
                'action'      => 'articles',
            ),
        ),
        'may_terminate' => true,
    ),
    
    /* MISC */
    'misc-sitemap' => array(
        'type' => 'Literal',
        'options' => array(
            'route'    => '/sitemap.xml',
            'defaults' => array(
                'controller'  => 'Simukti\Controller\Misc',
                'action'      => 'sitemap',
            ),
        ),
        'may_terminate' => true,
    ),
    'misc-rss' => array(
        'type' => 'Literal',
        'options' => array(
            'route'    => '/blog/rss.xml',
            'defaults' => array(
                'controller'  => 'Simukti\Controller\Misc',
                'action'      => 'rss',
            ),
        ),
        'may_terminate' => true,
    ),
);