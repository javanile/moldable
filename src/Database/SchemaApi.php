<?php
/**
 * Schema trait
 * manipulates database schema.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Database;

use Javanile\Moldable\Functions;

trait SchemaApi
{
    /**
     * Describe database each tables
     * with the specific prefix and her fields.
     *
     * @param null|mixed $only
     *
     * @return array return an array with database description schema
     */
    public function desc($only = null)
    {
        $schema = [];
        $prefix = strlen($this->getPrefix());
        $tables = $this->getTables();

        if (!$tables) {
            return $schema;
        }

        if (is_string($only)) {
            $only = [$only];
        }

        foreach ($tables as $table) {
            $model = substr($table, $prefix);

            if (!$only || in_array($model, $only)) {
                $schema[$model] = $this->descTable($table);
            }
        }

        return $schema;
    }

    /**
     * describe table.
     *
     * @param type $table
     *
     * @return type
     */
    public function descTable($table)
    {
        //
        $sql = "DESC `{$table}`";
        $fields = $this->getResults($sql);
        $desc = [];
        $count = 0;
        $before = false;

        //
        foreach ($fields as $field) {
            $field['First'] = $count === 0;
            $field['Before'] = $before;
            $desc[$field['Field']] = $field;
            $before = $field['Field'];
            $count++;
        }

        return $desc;
    }

    /**
     * Apply schema on the database.
     *
     * @param type       $schema
     * @param null|mixed $columns
     * @param null|mixed $notation
     *
     * @return type
     */
    public function apply($schema, $columns = null, $notation = null)
    {
        //
        if (is_string($schema)) {
            $schema = [
                $schema => is_string($columns)
                    ? [$columns => $notation]
                    : $columns,
            ];
        }

        //
        if (!$schema || count($schema) == 0 || !is_array($schema)) {
            $this->error('generic', 'empty schema not allowed');
        }

        //
        foreach ($schema as $model => $attributes) {
            if (!$attributes || count($attributes) == 0 || !is_array($attributes)) {
                $this->error('generic', "empty model '{$model}' not allowed");
            }
        }

        // retrive queries
        $queries = $this->diff($schema);

        // send all queries to align database
        if (count($queries) > 0) {
            foreach ($queries as $sql) {
                $this->execute($sql);
            }
        }

        return $queries;
    }

    /**
     * Update database table via schema.
     *
     * @param string $table  real table name to update
     * @param type   $schema
     * @param type   $parse
     *
     * @return type
     */
    public function applyTable($table, $schema, $parse = true)
    {
        // retrive queries
        $queries = $this->diffTable($table, $schema, $parse);

        // execute queries
        if ($queries && count($queries) > 0) {
            // loop throu all queries calculated and execute it
            foreach ($queries as $sql) {
                // execute each queries
                $this->execute($sql);
            }
        }

        return $queries;
    }

    /**
     * Generate SQL query to align database
     * compare real database and passed schema.
     *
     * @param type $schema
     * @param type $parse
     *
     * @return type
     */
    public function diff($schema, $parse = true)
    {
        // prepare
        if ($parse) {
            $this->getParser()->parse($schema);
        }

        // output container for rescued SQL query
        $queries = [];

        // loop throu the schema
        foreach ($schema as $table => &$attributes) {
            $table = $parse ? $this->getPrefix($table) : $table;
            $query = $this->diffTable($table, $attributes, false);

            if (count($query) > 0) {
                $queries = array_merge($queries, $query);
            }
        }

        return $queries;
    }

    /**
     * Generate query to align table.
     *
     * @param type $table
     * @param type $schema
     * @param type $parse
     *
     * @return type
     */
    public function diffTable($table, $schema, $parse = true)
    {
        // parse input schema if required
        if ($parse) {
            $this->getParser()->parseTable($schema);
            $table = $this->getPrefix().$table;
        }

        // if table no exists return sql statament for creating this
        if (!$this->tableExists($table, false)) {
            $sql = $this
                ->getWriter()
                ->createTable($table, $schema);

            return [$sql];
        }

        $queries = $this->diffTableQueries($table, $schema);

        return $queries;
    }

    /**
     * Generate query to align table.
     *
     * @param type $table
     * @param type $schema
     * @param type $parse
     *
     * @return type
     */
    private function diffTableQueries($table, &$schema)
    {
        // first order queries used as output array
        $foQueries = [];

        // second order queries used as output array
        $soQueries = [];

        // describe table get current table description
        $fields = $this->descTable($table);

        // test field definition
        foreach ($schema as $field => &$attributes) {
            $this->diffTableField(
                $table,
                $field,
                $attributes,
                $fields,
                $foQueries,
                $soQueries
            );
        }

        return $this->diffTableMergeQueries(
            $table,
            $fields,
            $foQueries,
            $soQueries
        );
    }

    /**
     * @param mixed $table
     * @param mixed $fields
     * @param mixed $foQueries
     * @param mixed $soQueries
     *
     * @return type
     */
    private function diffTableMergeQueries(
        $table,
        &$fields,
        &$foQueries,
        &$soQueries
    ) {
        $key = $this->diffTableFieldPrimaryKey($fields);

        if ($key && count($foQueries) > 0) {
            $writer = $this->getWriter();

            $foQueries[] = $writer->alterTableDropPrimaryKey($table);

            $fields[$key]['Key'] = '';
            $fields[$key]['Extra'] = '';

            $foQueries[] = $writer->alterTableChange(
                $table,
                $key,
                $fields[$key]
            );
        }

        return array_merge(array_reverse($foQueries), $soQueries);
    }

    /**
     * @param type $table
     * @param type $field
     * @param type $attributes
     * @param type $fields
     * @param type $foQueries
     * @param type $soQueries
     */
    private function diffTableField(
        $table,
        $field,
        &$attributes,
        &$fields,
        &$foQueries,
        &$soQueries
    ) {
        // check if column exists in current db
        if (!isset($fields[$field])) {
            $sql = $this
                ->getWriter()
                ->alterTableAdd($table, $field, $attributes);

            // add primary key column
            if ($attributes['Key'] == 'PRI') {
                $foQueries[] = $sql;
            } else {
                // add normal column
                $soQueries[] = $sql;
            }
        } elseif ($this->diffTableFieldAttributes($field, $attributes, $fields)) {
            // check if column need to be updated
            // compose alter table query with attributes
            $sql = $this
                ->getWriter()
                ->alterTableChange($table, $field, $attributes);

            // alter column that lose primary key
            if ($fields[$field]['Key'] == 'PRI' || $attributes['Key'] == 'PRI') {
                $foQueries[] = $sql;
            } else {
                // alter colum than not interact with primary key
                $soQueries[] = $sql;
            }
        }
    }

    /**
     * Evaluate diff between a field and their attributes
     * vs fields set definitions releaved direct from db.
     *
     * @param type $field
     * @param type $attributes
     * @param type $fields
     *
     * @return bool
     */
    private function diffTableFieldAttributes($field, &$attributes, &$fields)
    {
        // loop throd current column property
        foreach ($fields[$field] as $key => $value) {
            // if have a difference
            if ($attributes[$key] == $value) {
                continue;
            }

            //
            if ($this->isDebug()) {
                //echo '<pre style="background:#E66;color:#000;margin:0 0 1px 0;padding:2px 6px 3px 6px;border:1px solid #000;">';
                //echo '  difference: "'.$attributes[$key].'" != "'.$value.'" in '.$field.'['.$key.']</pre>';
            }

            return true;
        }

        return false;
    }

    /**
     * Return primary field name if have one.
     *
     * @param type $fields
     *
     * @return bool
     */
    private function diffTableFieldPrimaryKey(&$fields)
    {
        // loop throd current column property
        foreach ($fields as $field => &$attributes) {
            // lookitup by equal
            if ($attributes['Key'] == 'PRI') {
                return $field;
            }
        }

        return false;
    }

    /**
     * @param type       $schema
     * @param null|mixed $columns
     * @param null|mixed $notation
     */
    public function alter($schema, $columns = null, $notation = null)
    {
        //
        if (is_string($schema)) {
            $schema = [
                $schema => is_string($columns)
                     ? [$columns => $notation]
                     : $columns,
            ];
        }

        //
        $desc = $this->desc();

        //
        foreach ($schema as $table => $fields) {
            $desc[$table] = isset($desc[$table])
                && is_array($desc[$table])
                ? array_merge($desc[$table], $fields)
                : $fields;
        }

        //
        $this->apply($desc);
    }

    /**
     * @param type       $schema
     * @param null|mixed $column
     * @param null|mixed $notation
     */
    public function alterTable($schema, $column = null, $notation = null)
    {
        return $schema.$column.$notation;
    }

    /**
     * @param type       $schema
     * @param null|mixed $columns
     * @param null|mixed $notation
     */
    public function adapt($schema, $columns = null, $notation = null)
    {
        // fix one-line params
        if (is_string($schema)) {
            $schema = [
                $schema => is_string($columns)
                    ? [$columns => $notation]
                    : $columns,
            ];
        }

        $desc = $this->desc(array_keys($schema));

        foreach ($schema as $table => $fields) {
            if (!isset($desc[$table])) {
                $desc[$table] = $fields;
                continue;
            }
            foreach ($fields as $field => $attribute) {
                if (!isset($desc[$table][$field])) {
                    $desc[$table][$field] = $attribute;
                }
            }
        }

        return $this->apply($desc);
    }

    /**
     * Get values profile.
     *
     * @param mixed $values
     */
    public function profile($values)
    {
        $profile = [];

        foreach (array_keys($values) as $field) {
            $profile[$field] = '';
        }

        return $profile;
    }

    /**
     * printout database status and info.
     *
     * @param null|mixed $model
     */
    public function info($model = null)
    {
        $debug = $this->isDebug();
        $this->setDebug(false);

        if (is_null($model)) {
            $this->info($this->getModels());
        } elseif (is_array($model) && count($model) > 0) {
            foreach ($model as $m) {
                $this->info($m);
            }
        } elseif (is_array($model) && count($model) == 0) {
            echo '<pre><table border="1">'
               .'<tr><th>No database tables</th></tr></table></pre>'
               .'</table></pre>';
        } else {
            $desc = $this->desc($model);
            Functions::dumpSchema($desc);
        }

        $this->setDebug($debug);
    }

    /**
     * printout database status and info.
     */
    public function dumpSchema()
    {
        $debug = $this->isDebug();
        $this->setDebug(false);

        $schema = $this->desc();
        Functions::dumpSchema($schema);

        $this->setDebug($debug);
    }
}
