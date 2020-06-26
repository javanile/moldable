<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable;

use Stringy\Stringy;

class Functions
{
    /**
     * @param type $var
     */
    public static function varDump($var)
    {
        $style = 'padding:4px 6px 2px 6px;'
           .'background:#eee;'
           .'border:1px solid #ccc;'
           .'margin:0 0 1px 0;';

        echo '<pre style="'.$style.'">';
        var_dump($var);
        echo '</pre>';
    }

    /**
     * @param type  $title
     * @param type  $content
     * @param mixed $grid
     */
    public static function dumpGrid($grid, $title = null)
    {
        $key = key($grid);
        $colspan = count($grid) > 0 ? count((array) $grid[$key]) : 1;

        echo '<pre><table border="1" style="text-align:center;margin-bottom:1px;"><thead>';
        if ($title) {
            echo '<tr><th colspan="'.$colspan.'">'.$title.'</th></tr>';
        }

        if (isset($grid[$key]) && is_array($grid[$key])) {
            echo '<tr>';
            foreach (array_keys($grid[$key]) as $field) {
                echo '<th>'.$field.'</th>';
            }
            echo '</tr>';
        }

        echo '</thead><tbody>';
        foreach ($grid as $row) {
            echo '<tr>';
            if (is_array($row) || is_object($row)) {
                foreach ($row as $value) {
                    echo '<td>'.$value.'</td>';
                }
            } else {
                echo '<td>'.$row.'</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table></pre>';
    }

    /**
     * @param mixed $schema
     */
    public static function dumpSchema($schema)
    {
        $style = 'text-align:center;margin:10px 0;width:800px;';

        echo '<pre>';

        if (!$schema) {
            echo '<table border="1" style="'.$style.'">'
                .'<tr><th>No database tables</th></tr></table></pre>'
                .'</table>';
        } else {
            foreach ($schema as $table => $fields) {
                echo '<table border="1" style="'.$style.'">'
                    .'<tr><th colspan="9">'.$table.'</th></tr><tr><td>&nbsp;</td>';

                $first = key($fields);
                foreach (array_keys($fields[$first]) as $attributeName) {
                    echo '<th>'.$attributeName.'</th>';
                }

                echo '</tr>';

                foreach ($fields as $field => $attributes) {
                    echo '<tr><th>'.$field.'</th>';

                    foreach ($attributes as $value) {
                        echo '<td>'.$value.'</td>';
                    }

                    echo '</tr>';
                }

                echo '</table>';
            }

            echo '</pre>';
        }
    }

    /**
     * Throw new exception.
     *
     * @param type       $trace
     * @param type       $error
     * @param mixed      $slug
     * @param mixed      $exception
     * @param mixed      $offset
     * @param mixed      $message
     * @param mixed      $template
     * @param null|mixed $backtrace
     */
    public static function applyErrorTemplate(
        $message,
        $template,
        $backtrace = null,
        $offset = 0
    ) {
        switch ($template) {
            case 'in-method':
                $message .= ' in method '."'->".$backtrace[$offset]['function']."()'"
                    .' called at <b>'.$backtrace[$offset]['file'].'</b>'
                    .' on line <b>'.$backtrace[$offset]['line'].'</b>';
                break;
            case 'declared-at':
                $message .= ' declared at <b>'.$backtrace[$offset]['file'].'</b>';
                break;
            case 'required-for':
                $message .= ' required for file <b>'.$backtrace[$offset]['file'].'</b>'
                    .' on line <b>'.$backtrace[$offset]['line'].'</b>';
                break;
        }

        return $message;
    }

    /**
     * Throw new exception.
     *
     * @param type       $trace
     * @param type       $error
     * @param mixed      $slug
     * @param mixed      $exception
     * @param mixed      $offset
     * @param mixed      $message
     * @param mixed      $template
     * @param null|mixed $backtrace
     */
    public static function applyExceptionTemplate(
        $message,
        $template,
        $backtrace = null,
        $offset = 0
    ) {
        switch ($template) {
            case 'in-method':
                $message .= ' in method '."'->".$backtrace[$offset]['function']."()'"
                    .' called at '.$backtrace[$offset]['file']
                    .' on line '.$backtrace[$offset]['line'];
                break;
            case 'declared-at':
                $message .= ' declared at '.$backtrace[$offset]['file'];
                break;
            case 'required-for':
                $message .= ' required for file '.$backtrace[$offset]['file']
                    .' on line '.$backtrace[$offset]['line'];
                break;
        }

        return $message;
    }

    /**
     * Apply names conventions as camelCase or snake_case.
     *
     * @param mixed $convention
     * @param mixed $string
     */
    public static function applyConventions($convention, $string)
    {
        //
        switch ($convention) {
            case 'camel-case':
                return Stringy::create($string)->camelize();
            case 'upper-camel-case':
                return Stringy::create($string)->upperCamelize();
            case 'underscore':
                return Stringy::create($string)->underscored();
            default:
                return $string;
        }
    }

    /**
     * Generate banchmark line.
     *
     *
     * @param mixed $name
     */
    public static function benchmark()
    {
        $delta = 'asd';
        $style = 'background:#333;'
            .'color:#fff;'
            .'padding:2px 6px 3px 6px;'
            .'border:1px solid #000';
        $infoline = 'Time: '.$delta.' '.'Mem: ';

        echo '<pre style="'.$style.'">'.$infoline.'</pre>';
    }
}
