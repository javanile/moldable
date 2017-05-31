<?php
/**
 *
 *
 */

namespace Javanile\Moldable\Model;

trait JoinApi
{
    /**
     *
     *
     */
    public static function join(
        $fieldFrom = '__FIELD__',
        $fieldTo = null
    ) {
        //
        if (!is_string($fieldFrom)) {
            trigger_error('Required field to join', E_USER_ERROR);
        }

        //
        return [
            'Table'     => static::getTable(),
            'Class'     => static::getClassName(),
            'FieldFrom' => $fieldFrom,
            'FieldTo'   => $fieldTo ? $fieldTo : static::getSchemaFields(),
            'JoinKey'   => static::getPrimaryKey(),
        ];
    }
}
