<?php

namespace Javanile\Moldable\Model;

use Javanile\Moldable\Functions;

trait DebugApi
{
    public static function setDebug($flag)
    {
        static::getDatabase()->setDebug($flag);
    }

    public static function isDebug()
    {
        return static::getDatabase()->isDebug();
    }

    /**
     * @param type $list
     */
    public static function dump($list = 'all')
    {
        Functions::dumpGrid(
            $list == 'all' ? static::all() : $list,
            static::getTable()
        );
    }
}
