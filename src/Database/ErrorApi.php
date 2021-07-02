<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Database;

use Javanile\Moldable\Exception;
use Javanile\Moldable\Functions;

trait ErrorApi
{
    /**
     * Trigger a error.
     *
     * @param object $exception Exception catched with try-catch
     * @param mixed  $type
     */
    public function error($type, $exception)
    {
        switch ($type) {
            // Trigger a connection-with-database error.
            case 'connect':
                $slug = 'Moldable connection error, ';
                $backtrace = $this->_trace;
                $offset = 0;
                break;

            // Trigger a error in executed sql query.
            case 'execute':
                $slug = 'Moldable query error, ';
                $backtrace = debug_backtrace();
                $offset = 2;
                break;

            // Trigger a error in executed sql query.
            case 'generic':
                $slug = 'Moldable error, ';
                $backtrace = debug_backtrace();
                $offset = 1;
                break;

            // Trigger a error in executed sql query.
            /*
            default:
                $slug = 'Moldable uknown error, ';
                $backtrace = debug_backtrace();
                $offset = 0;
                break;*/
        }

        $this->throwException($slug, $exception, $backtrace, $offset);
    }

    protected function throwException($slug, $exception, $backtrace, $offset)
    {
        throw new Exception($slug.' - '.$exception->getMessage());
    }
}
