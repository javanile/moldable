<?php

/*
 * 
 * 
\*/
namespace Javanile\SchemaDB;

/**
 * 
 * 
 */
class ModelDeprecatedAPI extends ModelProtectedAPI 
{	
	
		
   
	

    //
    public static function build($data=null)
    {
        //
        return static::make($data);
    }
	
	
	
	//
    public static function map($data,$map)
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
	
	
	
	 // usefull mysql func
    public static function now()
    {
        //
        return date('Y-m-d H:i:s');
    }
	
}