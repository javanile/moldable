<?php
/**
 * Collect API to handle fields of a model.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Database;

trait FieldApi
{
    /**
     * Retrieve primary key name of specific model.
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

    /**
     *
     */
    public function getPrimaryKeyOrMainField($model)
    {
        $key = $this->getPrimaryKey($model);

        return $key ? $key : $this->getMainField($model);
    }

    /**
     *
     */
    public function getMainField($model)
    {
        // describe the model
        $desc = $this->desc($model);

        // search by fields for primary key
        foreach (array_keys($desc[$model]) as $field) {
            return $field;
        }
    }
}
