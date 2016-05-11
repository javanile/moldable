<?php

//
echo '<h1>SchemaDB tests</h1>';

//
function tests_tree($base, $head=false) {

    //
    $realpath = realpath($base);

    //
    $exclude = [
        '.',
        '..',
        'index.php',
        'common.php',        
        'config.php',
        'config.sample.php',
    ];

    //
    if ($head) {
        echo '<b>'.basename($realpath).'</b>';
    }
    
    //
    echo '<ul>';

    //
    $directories = [];

    //
    foreach (scandir($base) as $file) {

        //
        if (in_array($file, $exclude)) { continue; }

        //
        $path = $base.'/'.$file;

        //
        if (!is_dir($path)) {
            echo '<li><a href="'.$path.'" target="_blank">'.$file.'</a></li>';
        } else {
            $directories[] = $path;
        }
    }

    //
    if ($directories) {
        foreach ($directories as $path) {
            echo '<li>';
            tests_tree($path, true);
            echo '</li>';
        }
    }
    
    //
    echo '</ul>';
}

//
tests_tree('.');


