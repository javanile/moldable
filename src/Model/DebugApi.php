<?php

namespace Javanile\Moldable\Model;

use Javanile\Moldable\Functions;

trait DebugApi
{
    /**
     * @param mixed $type
     * @param mixed $exception
     *
     * @throws \Javanile\Moldable\Exception
     *
     * @internal param type $trace
     * @internal param type $error
     */
    public static function error($type, $exception)
    {
        $reflector = new \ReflectionClass(static::getClass());
        $offset = 0;

        switch ($type) {
            case 'class':
                $slug = 'Moldable class model error, ';
                $backtrace = [[
                    'file' => $reflector->getFileName(),
                    'line' => $reflector->getStartLine(),
                ]];
                break;

            case 'connection':
                $slug = 'Moldable connection error, ';
                $backtrace = null;
                break;
        }

        Functions::throwException($slug, $exception, $backtrace, $offset);
    }

    /**
     * Set debug mode for model class.
     *
     * @param mixed $flag
     */
    public static function setDebug($flag)
    {
        static::getDatabase()->setDebug($flag);
    }

    /**
     * Check if debug mode enabled.
     */
    public static function isDebug()
    {
        return static::getDatabase()->isDebug();
    }

    /**
     * Print-out list of element.
     *
     * @param string $list
     */
    public static function dump($list = 'all')
    {
        Functions::dumpGrid(
            $list == 'all' ? static::all() : $list,
            static::getTable()
        );
    }
}
