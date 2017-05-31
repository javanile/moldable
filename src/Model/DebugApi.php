<?php
/**
 *
 *
 */

namespace Javanile\SchemaDB\Model;

use Javanile\SchemaDB\Functions;

trait DebugApi
{
    /**
     *
     *
     */
    public static function setDebug($flag)
    {
        //
        static::getDatabase()->setDebug($flag);
    }
    
    /**
     *
     * @param type $list
     */
    public static function dump($list = '__null__')
    {       
        //
        Functions::gridDump(
            static::getTable(),
            $list != '__null__' ? $list : static::all()
        );
    }
    
    /**
     *
     * 
     */
    public static function desc()
    {
        //
        $table = static::getTable();

        //
        $desc = static::getDatabase()->descTable($table);

        //
        echo '<table border="1" style="text-align:center"><tr><th colspan="8">'.$table.'</td></th>';

        //
        $attributes = array_keys(reset($desc));

        //
        echo '<tr>';
        foreach ($attributes as $attribute) {
            echo '<th>'.$attribute.'</th>';
        }
        echo '</tr>';

        //
        foreach ($desc as $column) {
            echo '<tr>';
            foreach ($column as $attribute => $value) {
                echo '<td>'.$value.'</td>';
            }
            echo '</tr>';
        }

        //
        echo '</table>';
    }
}
