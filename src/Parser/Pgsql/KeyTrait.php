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
        //
        $aspects['Type'] = isset($params[0]) ? 'int('.$params[0].')' : 'integer';
        $aspects['Key'] = 'PRI';
        $aspects['Null'] = 'NO';
        $aspects['Default'] = '';
        $aspects['Extra'] = 'auto_increment';

        return $aspects;
    }
}
