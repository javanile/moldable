<?php

/*\
 * 
 * 
\*/
namespace SourceForge\SchemaDB;

/**
 * A collection of MySQL stataments builder
 * used with mysql query template and place-holder replacing
 */
class Mysql
{
    ##
    private static $default = array(
        'attributes' => array(
            'type' => 'int(10)',
        ),
    );

    ##
    public static function column_definition($d,$o=true)
    {
        ##
        $t = isset($d['Type']) ? $d['Type'] : static::$default['attributes']['type'];
        $u = isset($d['Null']) && ($d['Null']=="NO" || !$d['Null']) ? 'NOT NULL' : 'NULL';
        $l = isset($d['Default']) && $d['Default'] ? "DEFAULT '$d[Default]'" : '';
        $e = isset($d['Extra']) ? $d['Extra'] : '';
        $p = isset($d['Key']) && $d['Key'] == 'PRI' ? 'PRIMARY KEY' : '';

        ##
        $q = $t.' '.$u.' '.$l.' '.$e.' '.$p;

        ##
        if ($o) {
            $f = isset($d["First"])&&$d["First"] ? 'FIRST' : '';
            $b = isset($d["Before"])&&$d["Before"] ? 'AFTER '.$d["Before"] : '';
            $q.= ' '.$f.' '.$b;
        }

        ##

        return $q;
    }

    /**
     * Prepare sql code to create a table
     *
     * @param  string $t The name of table to create
     * @param  array  $s Skema of the table contain column definitions
     * @return string Sql code statament of CREATE TABLE
     */
    public static function createTable($t,$s)
    {
        ##
        $e = array();

        ## loop throut schema
        foreach ($s as $f=>$d) {

            ##
            if (is_numeric($f) && is_string($d)) {

                ##
                $f = $d;

                ##
                $d = array();
            }

            ##
            $e[] = $f.' '.static::column_definition($d,false);
        }

        ## implode
        $i = implode(',',$e);

        ## template sql to create table
        $q = "CREATE TABLE {$t} ({$i})";

        ## return the sql

        return $q;
    }

    ##
    public static function alter_table_add($t,$f,$d)
    {
        ##
        $c = Mysql::column_definition($d);

        ##
        $q = "ALTER TABLE {$t} ADD {$f} {$c}";

        ##

        return $q;
    }

    ## retrieve sql to alter table definition
    public static function alter_table_change($t,$f,$d)
    {
        ##
        $c = Mysql::column_definition($d);

        ##
        $q = "ALTER TABLE {$t} CHANGE {$f} {$f} {$c}";

        ##

        return $q;
    }

    ## retrive query to remove primary key
    public static function alter_table_drop_primary_key($t)
    {
        ##
        $q = "ALTER TABLE {$t} DROP PRIMARY KEY";

        ##

        return $q;
    }

    /**
     *
     * @param  type   $f
     * @return string
     */
    public static function select_fields($f,&$j)
    {
        ##
        $j = "";

        ##
        if (is_null($f)) {
            return '*';
        }

        ##
        else if (is_string($f)) {
            return $f;
        }

        ##
        else if (is_array($f)) {

            ##
            $s = array();

            ##

            ##
            foreach ($f as $k=>$v) {
                $s[] = is_numeric($k) ? $v : $k;
                if (preg_match('/([a-z]+)\.\*/i',$k,$d)) {
                    $j .= "INNER JOIN {$v} AS $d[1] ON ";
                }
            }

            ##

            return implode(',',$s);
        }
    }
}
