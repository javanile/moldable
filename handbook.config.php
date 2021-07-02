<?php
/**
 * DocForge Config File.
 *
 * PHP version 7
 *
 * @category   Config
 *
 * @author     Francesco Bianco <bianco@javanile.org>
 * @license    https://goo.gl/KPZ2qI  MIT License
 * @copyright  2015-2020 Javanile
 */

return [
    'autoload' => __DIR__.'/docs',
    'source' => __DIR__.'/docs',
    'output' => 'docs',
    'pages' => [
        'index' => '*.json',
        'insert' => 'Insert.php',
    ],
];
