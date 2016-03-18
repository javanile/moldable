<?php

//
error_reporting(E_ALL);
ini_set('display_errors',true);
if (function_exists('xdebug_disable')) { xdebug_disable(); }

//
$autoload = __DIR__.'/../../../autoload.php';

//
if (file_exists($autoload)) {
    include_once $autoload;
} else {
    spl_autoload_register(function ($class) {
        $classFile = __DIR__.'/../src/'.$class.'.php';
        if (file_exists($classFile)) {
            include_once $classFile;
        }
    });
}

//
echo '<style>pre{padding:0;margin:0;}</style>';

//
if (!file_exists(__DIR__.'/override.php')) {
	require_once __DIR__.'/config.php';
} else {
    require_once __DIR__.'/override.php';
}

