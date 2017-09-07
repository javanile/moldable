<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable;

use Stringy\Stringy;

class Functions
{
    /**
     * @param type $var
     */
    public static function varDump($var)
    {
        //
        $style = 'padding:4px 6px 2px 6px;'
               .'background:#eee;'
               .'border:1px solid #ccc;'
               .'margin:0 0 1px 0;';

        //
        echo '<pre style="'.$style.'">';
        var_dump($var);
        echo '</pre>';
    }

    /**
     * @param type $title
     * @param type $content
     */
    public static function dumpGrid($title, $content)
    {
        /*
        //
        $a = &$content;

        //
        $r = key($a);

        //
        $n = count($a) > 0 ? count((array) $a[$r]) : 1;

        //
        echo '<pre><table border="1" style="text-align:center;margin-bottom:1px;"><thead><tr><th colspan="'.$n.'">'.$title.'</th></tr>';

        //
        echo '<tr>';
        foreach ($a[$r] as $f=>$v) {
            echo '<th>'.$f.'</th>';
        }
        echo '</tr></thead><tbody>';

        //
        foreach ($a as $i=>$r) {
            echo '<tr>';
            foreach ($r as $f=>$v) {
                echo '<td>'.$v.'</td>';
            }
            echo '</tr>';
        }

        //
        echo '</tbody></table></pre>';
        */
        return '<div>'.$title.json_encode($content).'</div>';
    }

    /**
     * Throw new exception.
     *
     * @param type  $trace
     * @param type  $error
     * @param mixed $slug
     * @param mixed $exception
     * @param mixed $offset
     */
    public static function throwException(
        $slug,
        $exception,
        $trace = null,
        $offset = 0
    ) {
        $info = is_object($exception) ? $exception->getMessage() : $exception;
        //$code = is_object($exception) ? $exception->getCode() : 0;

        $message = $slug.$info
            .' in method '."'->".$trace[$offset]['function']."()'"
            .' called at '.$trace[$offset]['file']
            .' on line '.$trace[$offset]['line'];

        throw new Exception($message);
    }

    /**
     * Apply names conventions as camelCase or snake_case.
     *
     * @param mixed $convention
     * @param mixed $string
     */
    public static function applyConventions($convention, $string)
    {
        //
        switch ($convention) {
            case 'camel-case':
                return Stringy::create($string)->camelize();
            case 'upper-camel-case':
                return Stringy::create($string)->upperCamelize();
            case 'underscore':
                return Stringy::create($string)->underscored();
            default:
                return $string;
        }
    }

    /**
     * Generate banchmark line.
     *
     *
     * @param mixed $name
     */
    public static function bachmark($name)
    {
        $delta = 'asd';
        $style = 'background:#333;'
            .'color:#fff;'
            .'padding:2px 6px 3px 6px;'
            .'border:1px solid #000';
        $infoline = 'Time: '.$delta.' '.'Mem: ';

        return '<pre style="'.$style.'">'.$infoline.'</pre>';
    }
}
