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
    public function diff($schema,$parse=true)
    {
        ## prepare
        $s = $parse ? Parser::parseSchema($schema) : $schema;

        ## get prefix string
        $p = $this->getPrefix();

        ## output container for rescued SQL query
        $o = array();

        ## loop throu the schema
        foreach ($s as $t => $d) {

            ##
            $q = $this->diffTable($p.$t, $d, false);

            ##
            if (count($q)>0) {
                $o = array_merge($o, $q);
            }
        }

        ## return estimated sql query

        return $o;
    }

    /**
     * generate query to align table
     *
     * @param  type $table
     * @param  type $schema
     * @param  type $parse
     * @return type
     */
    public function diffTable($table,$schema,$parse=true)
    {
        ## parse input schema if required
        $s = $parse ? Parser::parseSchemaTable($schema) : $schema;

        ##
        $t = $parse ? $this->getPrefix() . $table : $table;

        ## sql query to test table exists
        $q = "SHOW TABLES LIKE '{$t}'";

        ## test if table exists
        $e = $this->getRow($q);

        ## if table no exists return sql statament for creating this
        if (!$e) {
            return array(Mysql::createTable($t, $s));
        }

        ## used as output array
        $o = array();

        ## used as output array
        $z = array();

        ## describe table get current table description
        $a = $this->descTable($t);

        ##
        $p = $this->diff_table_field_primary_key($a);

        ## test field definition
        foreach ($s as $f=>$d) {

            ##
            $this->diff_table_field($a,$f,$d,$t,$o,$z);
        }

        ##
        if ($p && count($z) > 0) {
            $a[$p]['Key'] = '';
            $a[$p]['Extra'] = '';
            $z[] = Mysql::alter_table_drop_primary_key($t);
            $z[] = Mysql::alter_table_change($t,$p,$a[$p]);
        }

        ##

        return array_merge(array_reverse($z),$o);
    }

    ##
    public function diff_table_field($a,$f,$d,$table,&$o,&$z)
    {
        ## check if column exists in current db
        if (!isset($a[$f])) {

            ##
            $q = Mysql::alter_table_add($table,$f,$d);

            ## add primary key column
            if ($d['Key'] == 'PRI') {
                $z[] = $q;
            }

            ## add normal column
            else {
                $o[] = $q;
            }
        }

        ## check if column need to be updated
        else if ($this->diff_table_field_attributes($a,$f,$d)) {

            ##
            $q = Mysql::alter_table_change($table,$f,$d);

            ## alter column that lose primary key
            if ($a[$f]['Key'] == 'PRI' || $d['Key'] == 'PRI') {
                $z[] = $q;
            }

            ## alter colum than not interact with primary key
            else {
                $o[] = $q;
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
    public function diff_table_field_attributes($a,$f,$d)
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

    ##
    public function diff_table_field_primary_key($a)
    {
        ## loop throd current column property
        foreach ($a as $f=>$d) {

            ## if have a difference
            if ($d['Key'] == 'PRI') { return $f; }
        }

        ##

        return false;
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

    ## describe table
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
     * @param type $schemadb
     */
    public static function setDefault($schemadb)
    {
        ## if no default SchemaDB connection auto-set then-self
        if (static::$default === null) {

            ## set current SchemaDB connection to default
            static::$default = &$schemadb;
        }
    }
}


