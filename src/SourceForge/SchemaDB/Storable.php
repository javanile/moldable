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
    ##
    public function store_update()
    {
        ## update database schema
        static::updateTable();

        ##
        $k = static::primary_key();

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
        static::getSchemaDB()->query($q);

        ##
        if ($k) {
            return $this->{$k};
        }

        ##
        else {
            return true;
        }
    }

    ##
    public function store_insert($force=false)
    {
        ##
        static::updateTable();

        ##
        $c = array();
        $v = array();
        $k = static::primary_key();

        ##
        foreach (static::getSchema() as $f=>$d) {

            ##
            if ($f==$k&&!$force) {continue;}

            ##
            $a = $this->{$f};
            $t = gettype($a);

            ##
            switch ($t) {

                ##
                case 'double':
                    $a = number_format($a,2,'.','');
                    break;

                ##
                case 'array':
                    schemadb::object_build($d,$a,$r);
                    $a = $r;
                    break;

            }

            ##
            $a = Parser::escape($a);

            ##
            $c[] = $f;
            $v[] = "'".$a."'";
        }

        ##
        $c = implode(',',$c);
        $v = implode(',',$v);

        ##
        $t = static::getTable();
        $q = "INSERT INTO {$t} ({$c}) VALUES ({$v})";

        ##
        static::getDatabase()->query($q);

        ##
        if ($k) {
            $i = static::getDatabase()->getLastId();
            $this->{$k} = $i;

            return $i;
        }

        ##
        else {
            return true;
        }
    }

}
