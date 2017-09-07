<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Database;

use Javanile\Moldable\Functions;

trait ErrorApi
{
    /**
     * Trigger a connection-with-database error.
     *
     * @param object $exception Exception catched with try-catch
     */
    public function errorConnect($exception)
    {
        Functions::throwException('Moldable connection error, ', $exception, $this->_trace, 0);
    }

    /**
     * Trigger a error in executed sql query.
     *
     * @param object $exception Exception catched with try-catch
     */
    public function errorExecute($exception)
    {
        $backtrace = debug_backtrace();

        Functions::throwException('Moldable query error, ', $exception, $backtrace, 2);
    }

    /**
     * Trigger a error in executed sql query.
     *
     * @param object $exception Exception catched with try-catch
     * @param mixed  $message
     */
    public function errorHandler($message)
    {
        $backtrace = debug_backtrace();

        Functions::throwException('Moldable error, ', $message, $backtrace, 1);
    }
}
