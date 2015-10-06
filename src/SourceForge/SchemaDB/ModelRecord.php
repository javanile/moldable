<?php

/*\
 * 
 * 
\*/
namespace SourceForge\SchemaDB;

/**
 * self methods of sdbClass
 *
 *
 */
class ModelRecord extends ModelPublicAPI
{
    ## constructor
    public function __construct()
    {
        ## update database schema
        static::updateTable();

        ## prepare field values strip schema definitions
        foreach (static::getSchemaFields() as $field) {

            ##
            $this->{$field} = Parser::getNotaionValue($this->{$field});
        }
    }
	
    
    
}
