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
class Storable extends Record
{
	/**
	 * 
	 * 
	 */
	public function __construct($values) {
		
		## call anchesto constr
		parent::__construct();
		
		## fill created object with passed values
		$this->fill($values);
	}

	/**
     * Auto-store element method
     *
     * @return type
     */
    public function store()
    {
        ## retrieve primary key
        $key = static::getPrimaryKey();

        ## based on primary key store action
        if ($key && $this->{$key} > 0) {

            ##
            return $this->storeUpdate();
        } else {

            ##
            return $this->storeInsert();
        }
    }  
	
	
	##
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
		
		echo 'I';
        ##
        static::updateTable();

        ##
        $fieldsArray = array();
        $valuesArray = array();
        
		##
		$key = static::getPrimaryKey();
				
		##
		$schema = static::getSchema();
		
        ##
        foreach ($schema as $field => &$column) {

            ##
            if ($field == $key && !$force) { continue; }

            ## get current value of attribute of object
            $value = static::rapresentation($this->{$field}, $column);
            
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
            $index = static::getDatabase()->getLastId();
            $this->{$key} = $index;

            return $index;
        }

        ##
        else {
            return true;
        }
    }

	/**
	 * 
	 * @param type $value
	 */
	public static function rapresentation($value, &$column) {
		
		##
		if (is_array($value) && isset($column['Class'])) {
		
			##
			$class = $column['Class'];
			
			##
			$object = new $class($value);
			
			##
			$index = $object->store();
			
			##
			return $index;
		}
		
		##
		return $value;		
	}
	
	
	/**
	 * 
	 * @param type $list
	 */
    public static function dump($list=null)
    {
        ##
        $a = $list ? $list : static::all();

        ##
        $t = static::getTable();

        ##
        $r = key($a);

        ##
        $n = count($a) > 0 ? count((array) $a[$r]) : 1;

        ##
        echo '<pre><table border="1" style="text-align:center"><thead><tr><th colspan="'.$n.'">'.$t.'</th></tr>';

        ##
        echo '<tr>';
        foreach ($a[$r] as $f=>$v) {
            echo '<th>'.$f.'</th>';
        }
        echo '</tr></thead><tbody>';

        ##
        foreach ($a as $i=>$r) {
            echo '<tr>';
            foreach ($r as $f=>$v) {
                echo '<td>'.$v.'</td>';
            }
            echo '</tr>';
        }

        ##
        echo '</tbody></table></pre>';
    }

	/**
	 * 
	 */
    public static function desc()
    {
        ##
        $t = static::getTable();

        ##
        $s = static::getSchemaDB()->desc_table($t);

        ##
        echo '<table border="1" style="text-align:center"><tr><th colspan="8">'.$t.'</td></th>';

        ##
        $d = reset($s);

        ##
        echo '<tr>';
        foreach ($d as $a=>$v) {
            echo '<th>'.$a.'</th>';
        }
        echo '</tr>';

        ##
        foreach ($s as $d) {
            echo '<tr>';
            foreach ($d as $a=>$v) {
                echo '<td>'.$v.'</td>';
            }
            echo '</tr>';
        }

        ##
        echo '</table>';
    }

}
