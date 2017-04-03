<?php
/**
 * 
 * 
\*/
namespace Javanile\SchemaDB;

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
    public static function triggerError($error, $trace=null, $offset=0)
    {
        //
        $message = is_object($error) ? $error->getMessage() : $error;
        
        //
        echo '<br>'
           . '<b>Fatal error</b>: '
           . $message.' in method <strong>'.$trace[$offset]['function'].'</strong> '
           . 'called at <strong>'.$trace[$offset]['file'].'</strong> on line <strong>'
           . $trace[$offset]['line'].'</strong>'.'<br>';

        //
        exit;
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