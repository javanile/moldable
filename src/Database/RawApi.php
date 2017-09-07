<?php
/**
 * Collect API to handle fields of a model.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Database;

trait RawApi
{
    /**
     * Retrieve primary key name of specific model.
     *
     * @param type $model
     *
     * @return type
     */
    public function raw($model)
    {
        // describe the passed model
        $desc = $this->desc($model);

        // search by fields for primary key
        foreach ($desc[$model] as $field => $aspects) {
            if ($aspects['Key'] == 'PRI') {
                return $field;
            }
        }
    }
}
