<?php

/*\
 * 
 * 
\*/
namespace SourceForge\SchemaDB;

/**
 *
 *
 *
 *
 */
class Model extends Table
{
	/**
	 * Bundle to collect info and stored cache
	 * 
	 * @var type 
	 */
    protected static $__ModelSettings = array(
		'exclude' => array(
            'class',
            'table',
            '__ModelSettings',
            '__ClassSettings',
        ),		
	); 
	
	/**
	 *
	 * @var type 
	 */
	protected static $__ClassSettings; 
	
	/**
	 * 
	 * 
	 */
	public function __construct() {
		
	}
		
	/**
	 * Retrieve static class name
	 * 
	 * @return type
	 */ 
    public static function getClass()
    {
        ##
        return isset(static::$class) ? static::$class : get_called_class();
    }
	
	/**
	 * 
	 */
	public static function hasModelSetting($setting) {
				
		##
		return isset(static::$__ModelSettings[$setting]);		
	}

	/**
	 * 
	 */
	public static function getModelSetting($setting) {
		
		##
		return static::$__ModelSettings[$setting];		
	}

	/**
	 * 
	 */
	public static function setModelSetting($setting,$value) {
		
		##
		static::$__ModelSettings[$setting] = $value;		
	}

	/**
	 * 
	 * @param type $setting
	 */
	public static function delModelSetting($setting) {
		
		## clear cached
        unset(static::$__ModelSettings[$setting]);		
	}
	
	/**
	 * 
	 */
	public static function hasClassSetting($setting) {
			
		##
		return isset(static::$__ClassSettings[$setting][static::getClass()]);		
	}

	/**
	 * 
	 */
	public static function getClassSetting($setting) {
		
		##
		return static::$__ClassSettings[$setting][static::getClass()];		
	}

	/**
	 * 
	 */
	public static function setClassSetting($setting,$value) {
		
		##
		static::$__ClassSettings[$setting][static::getClass()] = $value;		
	}

	/**
	 * 
	 * @param type $setting
	 */
	public static function delClassSetting($setting) {
		
		## clear cached
        unset(static::$__ClassSettings[$setting][static::getClass()]);		
	}
	
    /**
     * Load item from DB by primary key
     *
     * @param  type $id
     * @return type
     */
    public static function load($id,$fields=null)
    {
        ##
        $i = (int) $id;

        ##
        $t = static::getTable();

        ## get primary key
        $k = static::primary_key();

        ## parse SQL select fields
        $f = Mysql::select_fields($fields,$j);

        ## prepare SQL query
        $q = "SELECT {$f} FROM {$t} {$j} WHERE {$k}='{$i}' LIMIT 1";

        ## fetch data on database and return it

        return static::fetch($q, false, is_string($fields));
    }

    /**
     * Delete element by primary key or query
     *
     * @param type $query
     */
    public static function delete($query)
    {
        ##
        $t = static::getTable();

        ##
        if (is_array($query)) {

            ## where block for the query
            $h = array();

            ##
            if (isset($query['where'])) {
                $h[] = $query['where'];
            }

            ##
            foreach ($query as $k=>$v) {
                if ($k!='sort'&&$k!='where') {
                    $h[] = "{$k}='{$v}'";
                }
            }

            ##
            $w = count($h)>0 ? 'WHERE '.implode(' AND ',$h) : '';

            ##
            $s = "DELETE FROM {$t} {$w}";

            ## execute query
            static::getSchemaDB()->query($s);
        }

        ##
        else if ($query > 0) {

            ## prepare sql query
            $k = static::primary_key();

            ##
            $i = (int) $query;

            ##
            $q = "DELETE FROM {$t} WHERE {$k}='{$i}' LIMIT 1";

            ## execute query
            static::getSchemaDB()->query($q);
        }
    }

    ## drop table
    public static function drop($confirm=null)
    {
        ##
        if ($confirm !== 'confirm') {
            return;
        }

        ## prepare sql query
        $t = static::getTable();

        ##
        $q = "DROP TABLE IF EXISTS {$t}";

		##
		static::delModelSetting('update');
		
        ## execute query
        static::getDatabase()->query($q);
    }
}

