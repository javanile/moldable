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
class Record extends ModelAPI
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
	
    /**
     * Assign value and store object
     *
     * @param type $query
     */
    public function assign($query)
    {
        ##
        foreach ($query as $k => $v) {

            ##
            $this->{$k} = $v;
        }

        ##
        $this->store();
    }

    
}
