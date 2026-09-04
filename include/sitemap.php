<?php

// Webuzo server sitemap configuration for goride.run website

$basePath = '/home/londonserver/uk.goride.run/public';

if (isset($_SERVER['DOCUMENT_ROOT']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/sitemap.xml')) {
    $basePath = $_SERVER['DOCUMENT_ROOT'];
} elseif (isset($_SERVER['DOCUMENT_ROOT']) && file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/public/sitemap.xml')) {
    $basePath = dirname($_SERVER['DOCUMENT_ROOT']) . '/public';
}

return [
    'base_path' => $basePath,

    'files' => [
        'blog'   => 'sitemap-blog.xml',
        'static' => 'sitemap-static.xml',
        'index'  => 'sitemap.xml',
    ],

    'base_url' => 'https://www.goride.run/uk',
];
