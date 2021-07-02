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
    use Mysql\KeyTrait;
    use Mysql\TypeTrait;
    use Mysql\EnumTrait;
    use Mysql\ValueTrait;
    use Mysql\NumberTrait;
    use Mysql\StringTrait;
    use Mysql\CommonTrait;
    use Mysql\RelationTrait;
    use Mysql\DatetimeTrait;
}
