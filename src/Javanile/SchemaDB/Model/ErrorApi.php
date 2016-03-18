<?php
/**
 *
 *
 */

namespace Javanile\SchemaDB\Model;

use Javanile\SchemaDB\Utils;
use Javanile\SchemaDB\Exception;

trait ErrorApi
{
    /**
     *
     *
     * @param type $trace
     * @param type $error
     */
    public static function error($exception, $index=0)
    {
        //
        $trace = debug_backtrace();

        //
        echo '<br>'
           . '<b>Fatal error</b>: '
           . $exception->getMessage().' in method <strong>'.$trace[$index]['function'].'</strong> '
           . 'called at <strong>'.$trace[$index]['file'].'</strong> on line <strong>'
           . $trace[$index]['line'].'</strong>'."<br>";
        
        //
        die();
    }
}
