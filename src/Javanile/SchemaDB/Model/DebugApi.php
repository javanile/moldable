<?php
/**
 *
 *
 */

namespace Javanile\SchemaDB\Model;

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
}
