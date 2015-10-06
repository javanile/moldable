<?php

/*\
 * 
 * 
\*/
namespace SourceForge\SchemaDB;

/**
 * canonical name
 *
 *
 */
class Storable extends ModelPublicAPI
{
	/**
	 * Construct a storable object 
	 * with filled fields by values 
	 * 
	 * 
	 */
	public function __construct($values=null) {
		
		## call anchesto constr
		parent::__construct();
						
		## fill created object with passed values
		if ($values) {
			$this->fill($values);
		}
		
		## update related table
		static::updateTable();
	}

	/**
     * Auto-store element method
     *
     * @return type
     */
    public function store($values=null)
    {		
		##
		if (is_array($values)) {
			
			##
			foreach ($values as $field => $value) {
			
				##
				$this->{$field} = $value;
			}
		}
		
        ## retrieve primary key
        $key = static::getPrimaryKey();

        ## based on primary key store action
        if ($key && $this->{$key}) {
            return $this->storeUpdate();
        } 
		
		##
		else {
            return $this->storeInsert();
        }
    }  
		
	/**
	 * 
	 * 
	 * @return boolean
	 */
    public function storeUpdate()
    {
        ## update database schema
        static::updateTable();

        ##
        $k = static::getPrimaryKey();

        ##
        $e = array();

        ##
        foreach ($this->getFields() as $f) {

            ##
            if ($f == $k) { continue; }

            ##
            $v = Parser::encode($this->{$f});

            ##
            $e[] = "{$f} = '{$v}'";
        }

        ##
        $s = implode(',',$e);

        ##
        $t = static::getTable();

        ##
        $i = $this->{$k};

        ##
        $q = "UPDATE {$t} SET {$s} WHERE {$k}='{$i}'";

        ##
        static::getDatabase()->query($q);

        ##
        if ($k) {
            return $this->{$k};
        }

        ##
        else {
            return true;
        }
    }

    /**
	 * 
	 * 
	 * @param type $force
	 * @return boolean
	 */
    public function storeInsert($force=false)
    {
        ## update table if needed
        static::updateTable();

        ## collect field names for sql query
        $fieldsArray = array();
		
		## collect values for sql query
        $valuesArray = array();
        
		## get primary field name
		$key = static::getPrimaryKey();
				
		## get complete fields schema
		$schema = static::getSchema();
				
		##
        foreach ($schema as $field => &$column) {

            ##
            if ($field == $key && !$force) { continue; }

            ## get current value of attribute of object
            $value = static::insertRelationBefore($this->{$field}, $column);
            
            ##
            $fieldsArray[] = $field;
            $valuesArray[] = "'".$value."'";
        }

        ##
        $fields = implode(',', $fieldsArray);
        $values = implode(',', $valuesArray);

        ##
        $table = static::getTable();
		
		##
        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$values})";

        ##
        static::getDatabase()->query($sql);

        ##
        if ($key) {
			
			##
            $index = static::getDatabase()->getLastId();
        
			##
			foreach ($schema as $field => &$column) {
				
				##
				if ($field == $key && !$force) { continue; }

				##
				static::insertRelationAfter($this->{$field}, $column);				
			}
			
			##
			$this->{$key} = $index;

			##
            return $index;
        }

        ##        
		return true;        
    }

	/**
	 * 
	 * @param type $value
	 */
	private static function insertRelationBefore($value, &$column) {
		
		##
		if (!is_array($value)) {
			return $value;					
		}
			
		##
		switch ($column['Relation']) {
		
			##
			case '1:1': return static::insertRelationOneToOne($value, $column);
			
			##
			case '1:*':	return static::insertRelationOneToMany($value, $column);			
		}
				
	}
	
	/**
	 * 
	 */
	private static function insertRelationOneToOne($value, &$column) {
		
		##
		$class = $column['Class'];

		##
		$object = new $class($value);

		##
		$index = $object->store();

		##
		return $index;
	}
	
	/**
	 * 
	 * @param type $value
	 */
	private static function insertRelationAfter($value, &$column) {
		
		##
		if (!is_array($value)) {
			return $value;					
		}
			
		##
		switch ($column['Relation']) {
		
			##
			case '1:*':	return static::insertRelationOneToMany($value, $column);			
		}				
	}
	
	/**
	 * 
	 */
	private static function insertRelationOneToMany($values, &$column) {
		
		##
		$class = $column['Class'];

		##
		foreach($values as $value) {
			
			##
			$object = new $class($value);

			##
			$index = $object->store();
		}
		
		##
		return $index;
	}	
}
