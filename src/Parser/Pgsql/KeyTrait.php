<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Parser\Pgsql;

trait KeyTrait
{
    protected function getNotationAspectsPrimaryKey($notation, $aspects, $params)
    {
        $table = $aspects['Table'];
        $field = $aspects['Field'];

        $aspects['Type'] = isset($params[0]) ? 'int('.$params[0].')' : 'integer';
        $aspects['Key'] = 'PRIMARY KEY';
        $aspects['Null'] = 'NO';
        $aspects['Default'] = "nextval('public.{$table}_{$field}_seq'::regclass)";
        $aspects['Extra'] = '';

        return $aspects;
    }
}
