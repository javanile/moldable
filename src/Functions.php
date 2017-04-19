<?php
/**
 * 
 * 
\*/
namespace Javanile\Moldable;

class Functions
{	
    /**
     *
     * @param type $var
     */
    public static function varDump($var)
    {
        //
        $style = 'padding:4px 6px 2px 6px;'
               . 'background:#eee;'
               . 'border:1px solid #ccc;'
               . 'margin:0 0 1px 0;';
        
        //
        echo '<pre style="'.$style.'">';
        var_dump($var);
        echo '</pre>';
    }

    /**
     *
     * @param type $title
     * @param type $content
     */
    public static function gridDump($title, $content)
    {
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
    }

    /**
     *
     *
     * @param type $trace
     * @param type $error
     */
    public static function throwException($slug, $exception, $trace=null, $offset=0)
    {
        //
        $info = is_object($exception) ? $exception->getMessage() : $exception;
        $code = is_object($exception) ? $exception->getCode() : null;

        //
        $message = $slug . $info
            . ' in method '.$trace[$offset]['function']
            . ' called at '.$trace[$offset]['file']
            . ' on line '.$trace[$offset]['line'];

        //
        throw new Exception($message, $code);
    }
    
    /**
     * 
     * 
     * 
     */
    public static function runTest($name)
    {
        include __DIR__.'/../../../tests/'.$name.'.php';
    }
}