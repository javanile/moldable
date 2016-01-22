<?php
/**
 *
 *
 */

namespace Javanile\SchemaDB\Model;

trait DeprecatedApi
{	
    /**
     * 
     * @param type $data
     * @return type
     */
    public static function build($data=null)
    {
        //
        return static::make($data);
    }
	
	/**
     *
     * @param type $data
     * @param type $map
     * @return type
     */
    public static function map($data, $map)
    {
        //
        $o = static::make($data);

        //
        foreach ($map as $m=>$f) {
            $o->{$f} = isset($data[$m]) ? $data[$m] : '';
        }

        //
        return $o;
    }
	
	/**
     *
     * @return type
     */
	public static function now()
    {
        //
        return date('Y-m-d H:i:s');
    }
}