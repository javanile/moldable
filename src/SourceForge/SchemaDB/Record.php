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
class Record extends Model
{
    ## constructor
    public function __construct()
    {
        ## update database schema
        static::updateTable();

        ## prepare field values strip schema definitions
        foreach ($this->getFields() as $f) {

            ##
            $this->{$f} = Parser::getValue($this->{$f});
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

    /**
     * Auto-store element method
     *
     * @return type
     */
    public function store()
    {
        ## retrieve primary key
        $k = static::primary_key();

        ## based on primary key store action
        if ($k && $this->{$k}>0) {

            ##

            return $this->store_update();
        } else {

            ##

            return $this->store_insert();
        }
    }

    ##
    public function fill($array)
    {
        foreach ($this->getFields() as $f) {
            if (isset($array[$f])) {
                $this->{$f} = $array[$f];
            }
        }

        $k = $this->primary_key();

        if ($k) {
            $this->{$k} = isset($array[$k]) ? (int) $array[$k] : (int) $this->{$k};
        }
    }

    /**
     * Return fields names
     *
     * @return type
     */
    public function getFields()
    {
        ##
        $class = get_class($this);
        
		##
		$fields = array_keys(get_class_vars($class));
        
		##
		return array_diff($fields, static::getModelSetting('exclude'));		
    }

    ##
    public static function primary_key()
    {
        ##
        $s = static::getSchema();

        ##
        foreach ($s as $k=>$v) {

            ##
            if ($v === static::PRIMARY_KEY) {

                ##

                return $k;
            }
        }

        ##

        return false;
    }
}
