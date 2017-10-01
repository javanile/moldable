<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Model;

trait RawApi
{
    /**
     * Execute a raw query on database.
     *
     * @param type       $array
     * @param mixed      $sql
     * @param null|mixed $params
     * @param mixed      $singleRecord
     * @param mixed      $singleValue
     * @param mixed      $casting
     */
    public static function raw(
        $sql,
        $params = null
    ) {
        $results = static::getDatabase()->getResults($sql, $params);

        return $results;
    }
}
