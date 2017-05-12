<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable\Parser\Mysql;

trait Key
{
    /**
     *
     *
     */
    private static function getNotationAttributesPrimaryKey(
        $notation,
        $field,
        $before,
        $params
    ) {
        //
        $aspects = static::getNotationAttributesCommon($field, $before);

        //
        $aspects['Type'] = isset($params[0])
            ? 'int('.$params[0].')'
            : 'int(11)';

        //
        $aspects['Key'] = 'PRI';

        //
        $aspects['Null'] = 'NO';

        //
        $aspects['Default'] = '';

        //
        $aspects['Extra'] = 'auto_increment';

        //
        return $aspects;
    }
}
