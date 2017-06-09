<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Model;

use Javanile\SchemaDB\Functions;

trait PublicApi
{
    /**
     * Connectio Model-Class to database.
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
     *
     * @param type $values
     * @param type $map
     * @return \static
     */
    public static function make($values = null, $map = null, $prefix = null)
    {
        //
        $object = new static();

        //
        if ($values) {
            $object->fillSchemaFields($values, $map, $prefix);
        }

        //
        return $object;
    }

    /**
     * Encode/manipulate field on object
     * based on encode_ static method of class
     *
     * @param  type $$values
     * @return type
     */
    public function encode()
    {
        // . . .
    }

    /**
     *
     *
     * @param type $values
     * @return type
     */
    public function decode()
    {
        // . . .
    }
}
