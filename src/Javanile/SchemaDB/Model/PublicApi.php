<?php
/**
 * 
 * 
 */

namespace Javanile\SchemaDB\Model;

use Javanile\SchemaDB\Functions;

trait PublicApi 
{
    /**
     *
     * @param type $database
     */
    public static function connect($database=null)
    {
        //
        static::setDatabase($database);

        //
        static::applyTable();
    }

    /**
     *
     * @param type $values
     * @param type $map
     * @return \static
     */
    public static function make($values=null, $map=null, $prefix=null)
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