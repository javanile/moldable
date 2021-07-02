<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Writer;

class PgsqlWriter extends Writer
{
    /**
     * @param $table
     * @return string
     */
    public function tableExists($table)
    {
        // sql query to test if table exists
        $sql = "
           SELECT FROM information_schema.tables
           WHERE  table_schema = 'public'
           AND    table_name  = '{$table}'
           ";

        return $sql;
    }

    /**
     * Quote table or column names.
     *
     *
     * @param mixed $name
     */
    private function quote($name)
    {
        return '"'.$name.'"';
    }
}
