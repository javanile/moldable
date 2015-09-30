<?php
/*\
 *
 * Copyright (c) 2012-2015 Bianco Francesco
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files "schemadb", to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
\*/

/*\
 *
 * Thanks to SourceForge.net
 * for your mission on the web
 *
\*/
namespace SourceForge\SchemaDB;

/**
 * Main class prototyping a SchemaDB connection with MySQL database
 *
 * <code>
 * <?php
 * ## Create SchemaDB connection
 * $conn = new SchemaDB(array(
 *		'host' => 'localhost',
 *		'user' => 'root',
 *		'pass' => 'root',
 *		'name' => 'db_schemadb',
 *		'pref' => 'tbl_',
 * ));
 *
 * ## Create Table on database
 * $conn->update(array(
 *		'Table1' => array(
 *			'Field1' => 0,
 *			'Field2' => "",
 *		)
 * ));
 * ?>
 * </code>
 */
class Database extends Source
{
    /**
     * Constant to enable debug print-out
     */
    const DEBUG	= 1;

    /**
     * Currenti release version number
     */
    const VERSION = '0.3.0';

    /**
     *
     *
     * @var type
     */
    private static $default = null;

    /**
     * Construct and connect a SchemaDB drive
     * to mysql database best way to start use it
     *
     * @param array $args Array with connection parameters
     */
    public function __construct($args)
    {
		## 
		parent::__construct($args);

        ##
        static::setDefault($this);
    }

	    ##
    public function desc()
    {
        ##
        $p = $this->getPrefix();

        ##
        $q = "SHOW TABLES LIKE '{$p}%'";

        ##
        $l = $this->getResults($q);

        ##
        if (!count($l)) { return; }

        ##
        $r = array();

        ##
        foreach ($l as $t) {

            ##
            $t = reset($t);

            ##
            $r[$t] = $this->descTable($t);
        }

        ##
        return $r;
    }

    /**
	 * describe table
	 * 
	 * @param type $table
	 * @return type
	 */
    public function descTable($table)
    {
        ##
        $q = "DESC {$table}";

        ##
        $i = $this->getResults($q);

        ##
        $a = array();

        ##
        $n = 0;

        ##
        $b = false;

        ##
        foreach ($i as $j) {
            $j['Before'] = $b;
            $j['First']	= $n === 0;
            $a[$j['Field']] = $j;
            $b = $j['Field'];
            $n++;
        }

        ##

        return $a;
    }

    /**
     * Apply schema on the db
     *
     * @param  type $schema
     * @return type
     */
    public function apply($schema)
    {
        ##
        return $this->update($schema);
    }

    /**
     * Update db via schema
     *
     * @param  type $schema
     * @return type
     */
    public function update($schema)
    {
        ## retrive queries
        $q = $this->diff($schema);

        ## execute queries
        if (count($q)>0) {

            ##
            foreach ($q as $s) {

                ##
                $this->query($s);
            }
        }

        ## return queries

        return $q;
    }

    /**
     * Update database table via schema
     *
     * @param  string $table  real table name to update
     * @param  type   $schema
     * @return type
     */
    public function updateTable($table,$schema,$parse=true)
    {
        ## retrive queries
        $q = $this->diffTable($table,$schema,$parse);

        ## execute queries
        if ($q && count($q)>0) {

            ## loop throu all queries calculated and execute it
            foreach ($q as $s) {

                ## execute each queries
                $this->query($s);
            }
        }

        ## return queries
        return $q;
    }

    /**
     * Generate SQL query to align database
     * compare real database and passed schema
     *
     * @param  type $schema
     * @param  type $parse
     * @return type
     */
    public function diff(&$schema,$parse=true)
    {
        ## prepare
        if ($parse) { 
			Parser::parseSchema($schema);
		}

        ## get prefix string
        $prefix = $this->getPrefix();

        ## output container for rescued SQL query
        $queries = array();

        ## loop throu the schema
        foreach ($schema as $table => &$attributes) {

            ## 
            $sql = $this->diffTable($prefix . $table, $attributes, false);

            ##
            if (count($sql) > 0) {
                $queries = array_merge($queries, $sql);
            }
        }

        ## return estimated sql query
        return $queries;
    }

    /**
     * generate query to align table
     *
     * @param  type $table
     * @param  type $schema
     * @param  type $parse
     * @return type
     */
    public function diffTable($table, &$schema, $parse=true)
    {
        ## parse input schema if required
        if ($parse) { 
			
			##
			Parser::parseSchemaTable($schema);
			
			##
			$table = $this->getPrefix() . $table;
		}
				
        ## if table no exists return sql statament for creating this
        if (!$this->tableExists($table)) {
			
			## 
            return array(Mysql::createTable($table, $schema));
        }

		##
		return $this->diffTableQueries();
	}
	
    /**
     * generate query to align table
     *
     * @param  type $table
     * @param  type $schema
     * @param  type $parse
     * @return type
     */
    private function diffTableQueries($table, &$schema)
    {
        ## first order queries used as output array
        $foQueries = array();

        ## second order queries used as output array
        $soQueries = array();

        ## describe table get current table description
        $fields = $this->descTable($table);

        ## test field definition
        foreach ($schema as $field => &$attributes) {

            ##
            $this->diffTableField(
				$fields,
				$field,
				$attributes,
				$table,
				$foQueries,
				$soQueries
			);
        }

		return $this->diffTableMergeQueries($table, $fields, $foQueries, $soQueries);
	}
	
	/**
	 * 
	 * 
	 * 
	 * @return type
	 */
	private function diffTableMergeQueries($table, &$fields, &$foQueries, &$soQueries) {

		##
        $key = $this->diffTableFieldPrimaryKey($fields);

        ##
        if ($key && count($foQueries) > 0) {
			
			##
            $foQueries[] = Mysql::alterTableDropPrimaryKey($table);
            
			##
			$fields[$key]['Key'] = '';
            
			##
			$fields[$key]['Extra'] = '';
            
			##
			$foQueries[] = Mysql::alterTableChange($table, $key, $fields[$key]);
        }

        ##
        return array_merge(array_reverse($foQueries), $soQueries);
    }

	/**
	 * Test if a table exists
	 * 
	 * @param type $table
	 * @return type
	 */
	public function tableExists($table) {
		
		## sql query to test table exists
        $sql = "SHOW TABLES LIKE '{$table}'";

        ## test if table exists
        $exists = $this->getRow($sql);

		## return and cast test result
		return (boolean) $exists;
	}
	
    /**
	 * 
	 * @param type $table
	 * @param type $field
	 * @param type $attributes
	 * @param type $fields
	 * @param type $foQueries
	 * @param type $soQueries
	 */
    private function diffTableField($table, $field, &$attributes, &$fields, &$foQueries,&$soQueries)
    {
        ## check if column exists in current db
        if (!isset($fields[$field])) {

            ##
            $sql = Mysql::alterTableAdd($table, $field, $attributes);

            ## add primary key column
            if ($attributes['Key'] == 'PRI') {
                $foQueries[] = $sql;
            }

            ## add normal column
            else {
                $soQueries[] = $sql;
            }
        }

        ## check if column need to be updated
        else if ($this->diffTableFieldAttributes($field, $attributes, $fields)) {

            ##
            $sql = Mysql::alterTableChange($table, $field, $attributes);

            ## alter column that lose primary key
            if ($fields[$field]['Key'] == 'PRI' || $attributes['Key'] == 'PRI') {
                $foQueries[] = $sql;
            }

            ## alter colum than not interact with primary key
            else {
                $soQueries[] = $sql;
            }
        }
    }

    /**
     *
     *
     * @param  type    $a
     * @param  type    $f
     * @param  type    $d
     * @return boolean
     */
    private function diffTableFieldAttributes($a,$f,$d)
    {
        ## loop throd current column property
        foreach ($a[$f] as $k=>$v) {

            ##
            #echo $k.': '.$d[$k].' !== '.$v.' = '.($d[$k] != $v).'<br/>';

            ## if have a difference
            if ($d[$k] != $v) { return true; }
        }

        ##

        return false;
    }

    /**
	 * Return primary field name if have one
	 * 
	 * @param type $fields
	 * @return boolean
	 */
    private function diffTableFieldPrimaryKey(&$fields)
    {
        ## loop throd current column property
        foreach ($fields as $field => &$attributes) {

            ## lookitup by equal
            if ($attributes['Key'] == 'PRI') { 
				return $field; 				
			}
        }

        ##
        return false;
    }

    /**
     * Retrieve default SchemaDB connection
     *
     * @return type
     */
    public static function getDefault()
    {
        ## return static $default
        return static::$default;
    }

    /**
     *
     * @param type $database
     */
    public static function setDefault($database)
    {
        ## if no default SchemaDB connection auto-set then-self
        if (static::$default === null) {

            ## set current SchemaDB connection to default
            static::$default = &$database;
        }
    }
	
	/**
     * printout database status and info
     */
    public function dump()
    {
        ## describe databse
        $s = $this->desc();

        ##
        echo '<pre><table border="1" style="text-align:center">';

        ##
        if ($s) {

            ##
            foreach ($s as $t => $d) {

                ##
                echo '<tr><th colspan="9">'.$t.'</th></tr>';
                echo '<tr><td>&nbsp;</td>';
                $r = key($d);
                foreach ($d[$r] as $k=>$v) {
                    echo '<th>'.$k.'</th>';
                }
                echo '</tr>';
                foreach ($d as $f => $a) {
                    echo '<tr>';
                    echo '<th>'.$f.'</th>';
                    foreach ($a as $k=>$v) {
                        echo '<td>'.$v.'</td>';
                    }
                    echo '</tr>';
                }
            }
        } else {
            echo '<tr><th>No database tables</th></tr>';
        }

        ##
        echo '</table></pre>';
    }
}


