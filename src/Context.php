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
     * @var bool
     */
    private static $_useLaravel = true;

    /**
     * @var bool
     */
    private static $_container = null;

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
     *
     * @param mixed $flag
     */
    public static function useLaravel($flag)
    {
        static::$_useLaravel = (bool) $flag;
    }

    /**
     * Apply use laravel flag.
     *
     * @param mixed $flag
     * @param mixed $container
     */
    public static function registerContainer($container)
    {
        static::$_container = $container;
    }

    /**
     *
     */
    public static function checkContainer()
    {
        return static::$_container != null;
    }

    /**
     *
     */
    public static function getContainerDatabase()
    {
        return static::$_container->db;
    }
}
