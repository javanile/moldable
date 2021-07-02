<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Writer;

class MysqlWriter extends Writer
{
    /**
     * @param $table
     * @return string
     */
    public function tableExists($table)
    {
        // escape table name for query
        $escapedTable = str_replace('_', '\\_', $table);

        // sql query to test if table exists
        $sql = "SHOW TABLES LIKE '{$escapedTable}'";

        return $sql;
    }
}
