<?php

/*
 * 
 * 
\*/
namespace Javanile\SchemaDB;

/**
 * self methods of sdbClass
 *
 *
 */
class ModelRecord extends ModelTable
{
    /**
	 *  constructor
	 */
    public function __construct()
    {
        // update database schema
        static::updateTable();

        // prepare field values strip schema definitions
        foreach (static::getSchemaFields() as $field) {

            //
            $this->{$field} = SchemaParser::getNotaionValue($this->{$field});
        }
    }
	
    /**
	 * 
	 * 
	 * @param type $values
	 */
    public function fill($values)
    {
		//
        foreach (static::getSchemaFields() as $field) {
            
			//
			if (isset($values[$field])) {
                $this->{$field} = $values[$field];
            }
        }

		//
        $key = $this->getPrimaryKey();

		//
        if ($key) {
            $this->{$key} = isset($values[$key]) ? (int) $values[$key] : (int) $this->{$key};
        }
    }
    
}
