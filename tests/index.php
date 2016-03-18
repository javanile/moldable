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
        'config.php',
        'common.php',
        'override.php',
    ];

    //
    if ($head) {
        echo '<b>'.basename($realpath).'</b>';
    }
    
    //
    echo '<ul>';

    //
    foreach (scandir($base) as $file) {

        //
        if (in_array($file, $exclude)) { continue; }

        //
        echo '<li>';

        //
        $path = $base.'/'.$file;

        //
        if (is_dir($path)) {
            tests_tree($path, true);
        } else {
            echo '<a href="'.$path.'" target="_blank">'.$file.'</a>';
        }
        
        //
        echo '</li>';
    }
    
    //
    echo '</ul>';
}

//
tests_tree('.');


