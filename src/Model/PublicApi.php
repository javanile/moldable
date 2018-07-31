<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Model;

trait PublicApi
{
    /**
     * Connect Model-Class to database.
     *
     * @param type $database
     */
    public static function connect($database = null)
    {
        static::resetClass();
        static::setDatabase($database);
        static::applySchema();
    }

    /**
     * @param type       $values
     * @param type       $map
     * @param null|mixed $prefix
     *
     * @return \static
     */
    public static function create($values = null, $map = null, $prefix = null)
    {
        $object = new static();
        if ($values) {
            $object->fillSchemaFields($values, $map, $prefix);
        }

        return $object;
    }

    /**
     * Encode/manipulate field on object
     * based on encode_ static method of class.
     *
     * @return type
     */
    public function encode()
    {
        // . . .
    }

    /**
     * @return type
     */
    public function decode()
    {
        // . . .
    }
}
