<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Parser;

interface Parser
{
    const CLASSREGEX = '([A-Za-z_][0-9A-Za-z_]*(\\\\[A-Za-z_][0-9A-Za-z_]*)*)([^\*.]*)';
}
