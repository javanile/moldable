<?php
/**
 *
 *
 */

namespace Javanile\SchemaDB\Database;

use Javanile\SchemaDB\Functions;

trait ErrorApi
{
    /**
     *
     *
     */
    public function errorConnect($exception)
    {
        //
        Functions::triggerError($exception, $this->_trace, 0);
    }

    /**
     *
     *
     */
    public function errorExecute($exception)
    {

        var_dump($exception);
        die();
    }
}
