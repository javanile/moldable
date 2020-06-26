<?php

namespace Javanile\Moldable\Model;

use Javanile\Moldable\Functions;

trait DebugApi
{
    /**
     * @param mixed $type
     * @param mixed $exception
     * @param mixed $template
     * @param mixed $offset
     *
     * @throws \Javanile\Moldable\Exception
     *
     * @internal param type $trace
     * @internal param type $error
     */
    public static function error($type, $exception, $template, $offset = 0)
    {
        $errorMode = static::getClassConfig('error-mode');
        if ($errorMode == 'silent') {
            return;
        }

        $reflector = new \ReflectionClass(static::getClass());
        $exception = is_object($exception) ? $exception->getMessage() : $exception;

        switch ($type) {
            case 'class':
                $message = 'Moldable model class error, '.$exception;
                $backtrace = [[
                    'file' => $reflector->getFileName(),
                    'line' => $reflector->getStartLine(),
                ]];
                break;

            case 'connection':
                $message = 'Moldable connection error, '.$exception;
                $backtrace = debug_backtrace();
                break;
        }

        if ($errorMode == 'exception') {
            return Functions::applyExceptionTemplate($message, $template, $backtrace, $offset);
        }

        return Functions::applyErrorTemplate($message, $template, $backtrace, $offset);
    }

    /**
     * Set debug mode for model class.
     *
     * @param mixed $flag
     */
    public static function setDebug($flag)
    {
        static::getDatabase()->setDebug($flag);
    }

    /**
     * Check if debug mode enabled.
     */
    public static function isDebug()
    {
        return static::getDatabase()->isDebug();
    }

    /**
     * Print-out list of element.
     *
     * @param string $list
     */
    public static function dump($list = 'all')
    {
        Functions::dumpGrid(
            $list == 'all' ? static::all() : $list,
            static::getTable()
        );
    }
}
