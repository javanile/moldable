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
     * @var boolean
     */
    private static $_useLaravel = true;

    /**
     * Check if run inside laravel.
     */
    public static function checkLaravel()
    {
        return class_exists('Illuminate\Database\Capsule\Manager')
            && defined('LARAVEL_START') && microtime(true) > LARAVEL_START
            && static::$_useLaravel;
    }

    /**
     * Apply use laravel flag.
     */
    public static function useLaravel($flag)
    {
        static::$_useLaravel = (bool) $flag;
    }
}
