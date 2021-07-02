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

    /**
     *
     */
    public function descTable($table)
    {
        return "DESC `{$table}`";
    }

    /**
     * Retrieve sql to alter table definition.
     *
     * @param type  $t
     * @param type  $f
     * @param type  $d
     * @param mixed $table
     * @param mixed $field
     * @param mixed $attributes
     *
     * @return type
     */
    public function alterTableChange($table, $field, $attributes, $diffAttributes)
    {
        $quotedTable = $this->quote($table);
        $quotedField = $this->quote($field);
        $column = $this->columnDefinition($field, $attributes);

        return ["ALTER TABLE {$quotedTable} CHANGE COLUMN {$quotedField} {$column}"];
    }

    /**
     * @param bool $matchPrefix
     * @return string
     */
    public function getTables($prefix = null)
    {
        if ($prefix) {
            $escapedPrefix = str_replace('_', '\\_', $prefix);

            return "SHOW TABLES LIKE '{$escapedPrefix}%'";
        }

        return 'SHOW TABLES';
    }
}
