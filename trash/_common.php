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
        $classFile = __DIR__.'/../src/'.strtr($class,'\\','/').'.php';
        if (file_exists($classFile)) {
            include_once $classFile;
        }
    });
}

//
echo '<style>pre{padding:0;margin:0;}</style>';

//
$config = __DIR__.'/config.php';

//
if (file_exists($config)) {
    include_once $config;
} else {
    die("rename config.sample.php to confing.php");
}

