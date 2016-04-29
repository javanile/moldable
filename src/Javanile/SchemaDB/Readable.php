<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.4
 *
 * @author Francesco Bianco
 */
namespace Javanile\SchemaDB;

use Javanile\SchemaDB\Notations;

class Readable implements Notations
{
    use Model\LoadApi;
    use Model\JoinApi;
    use Model\ErrorApi;
    use Model\ClassApi;
    use Model\TableApi;
    use Model\FieldApi;
    use Model\SchemaApi;
    use Model\UpdateApi;
    use Model\DebugApi;
    use Model\PublicApi;
    use Model\DatabaseApi;

    //
    static $__adamant = true;
}