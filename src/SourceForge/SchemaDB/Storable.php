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

    ##
    public function store_insert($force=false)
    {
        ##
        static::updateTable();

        ##
        $c = array();
        $v = array();
        $k = static::getPrimaryKey();

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
