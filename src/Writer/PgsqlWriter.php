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
    //
    protected static $defaults = [
        'Attributes' => [
            'Type' => 'integer',
        ],
    ];

    /**
     * @param $table
     * @return string
     */
    public function tableExists($table)
    {
        return "SELECT table_name 
                  FROM information_schema.tables 
                 WHERE table_schema = 'public'
                   AND table_name = '{$table}'";
    }

    /**
     * @param bool $matchPrefix
     * @return string
     */
    public function getTables($prefix = null)
    {
        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'";

        if ($prefix) {
            $escapedPrefix = str_replace('_', '\\_', $prefix);
            $sql .= " AND table_name LIKE '{$escapedPrefix}%'";
        }

        return $sql;
    }

    /**
     *
     */
    public function descTable($table)
    {
        return "SELECT c.column_name AS \"Field\"
                     , CONCAT(c.data_type, '(', c.character_maximum_length, ')') AS \"Type\"
                     , tc.constraint_type AS \"Key\"
                  FROM information_schema.columns AS c
             LEFT JOIN information_schema.key_column_usage AS kcu
                    ON c.table_name = kcu.table_name
                   AND c.column_name = kcu.column_name 
             LEFT JOIN information_schema.table_constraints AS tc
                    ON tc.constraint_name = kcu.constraint_name
                 WHERE c.table_schema = 'public'
                   AND c.table_name = '{$table}'";
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
        $type = $attributes['Type'];
        $quotedTable = $this->quote($table);
        $quotedField = $this->quote($field);

        $sql = [];
        if (in_array('Type', $diffAttributes)) {
            $sql[] = "ALTER TABLE {$quotedTable} ALTER COLUMN {$quotedField} TYPE {$type}";
        }

        return $sql;
    }

    /**
     * Quote table or column names.
     *
     *
     * @param mixed $name
     */
    public function quote($name)
    {
        return '"'.$name.'"';
    }
}
