<?php

/*
 * 
 * 
\*/
namespace Javanile\SchemaDB;

/**
 *
 *
 *
 */
class Debug 
{	
	/**
     *
     * @param type $var
     */
	public static function varDump($var)
    {
		echo '<pre style="padding:4px 6px 2px 6px; background:#eee;border:1px solid #ccc; margin:0 0 1px 0;">';			
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
        echo '<pre><table border="1" style="text-align:center"><thead><tr><th colspan="'.$n.'">'.$title.'</th></tr>';

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
}