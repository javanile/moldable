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
    public static function dump($list=null)
    {       
        //
        Functions::gridDump(
            static::getTable(),
            $list ? $list : static::all()
        );
    }
}
