<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.4
 *
 * @author Francesco Bianco
 */
namespace Javanile\SchemaDB\Database;

trait FieldApi
{
    /**
     * Retrieve primary key name of specific model on table.
     *
     * @param type $model
     * @return type
     */
    public function getPrimaryKey($model)
    {
        // describe the model
        $desc = $this->desc($model);

        // search by fields for primary key
        foreach ($desc[$model] as $field => $aspects) {
            if ($aspects['Key'] == 'PRI') {
                return $field;
            }
        }
    }
}