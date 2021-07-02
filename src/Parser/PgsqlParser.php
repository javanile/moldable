<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Parser;

class PgsqlParser extends Parser
{
    use Pgsql\KeyTrait;
    use Pgsql\TypeTrait;
    use Pgsql\EnumTrait;
    use Pgsql\ValueTrait;
    use Pgsql\NumberTrait;
    use Pgsql\StringTrait;
    use Pgsql\CommonTrait;
    use Pgsql\RelationTrait;
    use Pgsql\DatetimeTrait;
}
