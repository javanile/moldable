<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Model;

use Javanile\Moldable\Functions;

trait ErrorApi
{
    /**
     *
     *
     * @param type $trace
     * @param type $error
     */
    public static function error($exception)
    {
        $backtrace = debug_backtrace();

        Functions::throwException("Moldable model error, ", $exception, $backtrace, 3);
    }
}
