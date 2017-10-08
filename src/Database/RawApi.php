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
     * @param type  $model
     * @param mixed $sql
     *
     * @return type
     */
    public function raw($sql)
    {
        $results = $this->getResults($sql);

        return $results;
    }
}
