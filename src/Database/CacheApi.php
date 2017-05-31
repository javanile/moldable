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

trait CacheApi
{
    /**
     *
     */
    protected $_cache = [];

    /**
     * Trigger a connection-with-database error.
     *
     * @param object $exception Exception catched with try-catch
     */
    public function setCache($key, $value)
    {
        $this->_cache[$key] = $value;
        //Functions::throwException("Moldable connection error, ", $exception, $this->_trace, 0);
    }

    /**
     * Trigger a error in executed sql query.
     *
     * @param object $exception Exception catched with try-catch
     */
    public function getCache($key)
    {
        return $this->_cache[$key];
    }

    /**
     * Trigger a error in executed sql query.
     *
     * @param object $exception Exception catched with try-catch
     */
    public function hasCache($key)
    {
        return isset($this->_cache[$key]);
    }
}
