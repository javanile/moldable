<?php
/**
 *
 *
 */

namespace Javanile\SchemaDB\Model;

use Javanile\SchemaDB\Exception;

trait ErrorApi
{
    /**
     *
     *
     * @param type $trace
     * @param type $error
     */
    public static function error($exception, $trace, $offset=0)
    {
        //
        $message = is_string($exception)
                 ? $exception : $exception->getMessage();
        
        //
        echo '<br><b>Fatal error</b>: '.$message;
           
        //
        if (isset($trace[$offset]['function'])) {
            echo ' in method <strong>'.$trace[$offset]['function'].'</strong> ';
        }
                    
        //
        echo ' called at <strong>'.$trace[$offset]['file'].'</strong>'
           . ' on line <strong>'.$trace[$offset]['line'].'</strong><br>';
        
        //
        exit();
    }
}
