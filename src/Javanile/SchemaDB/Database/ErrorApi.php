<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.4
 *
 * @author Francesco Bianco
 */

namespace Javanile\SchemaDB\Database;

use Javanile\SchemaDB\Functions;

trait ErrorApi
{
    /**
     * Trigger a connection with database error.
     *
     * @param object $exception Exception catched with try-catch
     */
    public function errorConnect($exception)
    {
        //
        Functions::triggerError($exception, $this->_trace, 0);
    }

    /**
     * Trigger a error in executed sql query.
     *
     * @param object $exception Exception catched with try-catch
     */
    public function errorExecute($exception)
    { 
        var_dump($exception);
        die();
    }
}
