<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Writer;

class MysqlWriter implements Writer
{
    //
    private static $defaults = [
        'Attributes' => [
            'Type' => 'int(11)',
        ],
    ];

    //
    public function columnDefinition($aspects, $order = true)
    {
        $Key = isset($aspects['Key'])
            && $aspects['Key'] == 'PRI'
            ? 'PRIMARY KEY' : '';

        $Type = isset($aspects['Type'])
            ? strtolower($aspects['Type'])
            : static::$defaults['Attributes']['Type'];

        $Null = isset($aspects['Null'])
            && ($aspects['Null'] == 'NO' || !$aspects['Null'])
            ? 'NOT NULL' : 'NULL';

        $Extra = isset($aspects['Extra']) ? $aspects['Extra'] : '';

        if (!isset($aspects['Default'])
            || $aspects['Default'] === 'NO'
            || $aspects['Default'] === ''
            || $aspects['Key']) {
            $Default = '';
        } elseif ($aspects['Default'] === 'CURRENT_TIMESTAMP') {
            $Default = 'DEFAULT CURRENT_TIMESTAMP';
        } else {
            $Default = 'DEFAULT '."'".$aspects['Default']."'";
        }

        $sql = $Type.' '.$Null.' '.$Default.' '.$Key.' '.$Extra;

        if ($order) {
            $First = isset($aspects['First']) && $aspects['First'] ? 'FIRST' : '';
            $Before = isset($aspects['Before']) && $aspects['Before'] ? 'AFTER '.$aspects['Before'] : '';
            $sql .= ' '.$First.' '.$Before;
        }

        return trim($sql);
    }

    /**
     * Prepare sql code to create a table.
     *
     * @param string $table  The name of table to create
     * @param array  $schema Skema of the table contain column definitions
     *
     * @return string Sql code statament of CREATE TABLE
     */
    public function createTable($table, $schema)
    {
        //
        $columnsArray = [];

        // loop throut schema
        foreach ($schema as $field => $attributes) {
            if (is_numeric($field) && is_string($attributes)) {
                $field = $attributes;
                $attributes = [];
            }

            //
            $column = $this->columnDefinition($attributes, false);

            //
            $columnsArray[] = "`{$field}` {$column}";
        }

        // implode
        $columns = implode(',', $columnsArray);

        // template sql to create table
        $sql = "CREATE TABLE `{$table}` ({$columns})";

        // return the sql
        return $sql;
    }

    /**
     * @param type $table
     * @param type $field
     * @param type $attributes
     *
     * @return type
     */
    public function alterTableAdd($table, $field, $attributes)
    {
        //
        $column = $this->columnDefinition($attributes);

        //
        $sql = "ALTER TABLE `{$table}` ADD COLUMN `{$field}` {$column}";

        //
        return $sql;
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
    public function alterTableChange($table, $field, $attributes)
    {
        //
        $column = $this->columnDefinition($attributes);

        //
        $sql = "ALTER TABLE `{$table}` CHANGE COLUMN `{$field}` `{$field}` {$column}";

        //
        return $sql;
    }

    // retrive query to remove primary key
    public function alterTableDropPrimaryKey($table)
    {
        //
        $sql = "ALTER TABLE `{$table}` DROP PRIMARY KEY";

        //
        return $sql;
    }

    /**
     * @param type  $f
     * @param mixed $fields
     * @param mixed $tableAlias
     * @param mixed $join
     *
     * @return string
     */
    public function selectFields($fields, $tableAlias, &$join)
    {
        //
        if (!$fields) {
            return '*';
        } elseif (is_string($fields)) {
            return $fields;
        } elseif (!is_array($fields)) {
            static::error('selectFields require array');
        }

        //
        $join = '';

        //
        $aliasTable = [];

        //
        $selectFields = [];

        //
        foreach ($fields as $field => $definition) {
            if (is_numeric($field)) {
                $field = $definition;
                $definition = null;
            }

            //
            if (!$definition) {
                $selectFields[] = $this->selectFieldsSingletoneField(
                    $field,
                    $tableAlias
                );
                continue;
            }

            //
            if (is_string($definition)) {
                $selectFields[] = $definition.' AS '.$field;
                continue;
            }

            //
            if (is_array($definition)) {
                $class = $definition['Class'];

                //
                $aliasTable[$class] = isset($aliasTable[$class])
                                    ? $aliasTable[$class] + 1
                                    : 1;

                //
                $joinAlias = $aliasTable[$class] > 1
                           ? $class.''.$aliasTable[$class]
                           : $class;

                //
                $joinTable = $definition['Table'];

                //
                $joinKey = $joinAlias.'.'.$definition['JoinKey'];

                //
                $fieldFrom = $definition['FieldFrom'];

                if ($fieldFrom == '__FIELD__') {
                    $fieldFrom = $field;
                }

                //
                $join .= " LEFT JOIN {$joinTable} AS {$joinAlias}"
                       ." ON {$joinKey} = {$fieldFrom}";

                if (is_array($definition['FieldTo'])) {
                    foreach ($definition['FieldTo'] as $nextFieldTo) {
                        $fieldAlias = $field.'__'.$nextFieldTo;
                        $fieldTo = $joinAlias.'.'.$nextFieldTo;
                        $selectFields[] = $fieldTo.' AS '.$fieldAlias;
                    }
                }

                continue;
            }
        }

        return implode(', ', $selectFields);
    }

    /**
     * @param type  $field
     * @param mixed $tableAlias
     *
     * @return type
     */
    public function selectFieldsSingletoneField($field, $tableAlias)
    {
        //
        if (preg_match('/^[a-z_][a-z0-9_]*$/i', $field)) {
            return $tableAlias ? $tableAlias.'.'.$field : $field;
        } else {
            return $field;
        }
    }

    /**
     * @param mixed $whereArray
     */
    public function whereByArray($whereArray)
    {
        return $whereArray
            ? 'WHERE '.implode(' AND ', $whereArray)
            : '';
    }

    /**
     * @param mixed $order
     */
    public function orderBy($order)
    {
        if (is_array($order)) {
            $sql = [];
            foreach ($order as $field => $asc) {
                $sql[] = '`'.$field.'`'.' '.$asc;
            }

            return implode(', ', $sql);
        }

        return $order;
    }

    /**
     * Quote table or column names.
     *
     *
     * @param mixed $name
     */
    private function quote($name)
    {
        return '`'.$name.'`';
    }
}
