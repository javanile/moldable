<?php
/**
 *
 *
 */

namespace Javanile\Moldable\Model;

use Javanile\Moldable\Functions;

trait DebugApi
{
    /**
     *
     *
     */
    public static function setDebug($flag)
    {
        static::getDatabase()->setDebug($flag);
    }

    /**
     *
     *
     */
    public static function getDebug()
    {
        return static::getDatabase()->getDebug();
    }

    /**
     *
     * @param type $list
     */
    public static function dump($list = '__null__')
    {       
        $html = Functions::dumpGrid(
            static::getTable(),
            $list != '__null__' ? $list : static::all()
        );

        return $html;
    }
}
