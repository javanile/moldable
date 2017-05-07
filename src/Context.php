<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable;

final class Context
{
    /**
     *
     */
    private static $_useLaravel = true;

    /**
     *
     */
    public static function checkLaravel()
    {
        return class_exists('Illuminate\Database\Capsule\Manager')
            && defined('LARAVEL_START') && microtime(true) > LARAVEL_START
            && static::$_useLaravel;
    }

    /**
     *
     */
    public static function useLaravel($flag)
    {
        static::$_useLaravel = (bool) $flag;
    }
}
