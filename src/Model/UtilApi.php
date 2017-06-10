<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Model;

trait UtilApi
{
    /**
     *
     * @return type
     */
    public static function now()
    {
        return date('Y-m-d H:i:s');
    }
}
