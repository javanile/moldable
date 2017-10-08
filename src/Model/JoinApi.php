<?php

namespace Javanile\Moldable\Model;

trait JoinApi
{
    public static function join(
        $fieldFrom = '__FIELD__',
        $fieldTo = null
    ) {
        /*
        if (!is_string($fieldFrom)) {
            static::error('class', 'required field to join');
        }
        */

        return [
            'Table'     => static::getTable(),
            'Class'     => static::getClassName(),
            'FieldFrom' => $fieldFrom,
            'FieldTo'   => $fieldTo ? $fieldTo : static::getSchemaFields(),
            'JoinKey'   => static::getPrimaryKey(),
        ];
    }
}
