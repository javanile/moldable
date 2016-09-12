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
    use Model\ReadApi;
    use Model\JoinApi;
    use Model\TableApi;
    use Model\FieldApi;
    use Model\FetchApi;
    use Model\ClassApi;
    use Model\SchemaApi;
    use Model\DatabaseApi;
    use Model\DebugApi;
    //
    //use Model\ErrorApi;
    //
    //
    //use Model\UpdateApi;
    //
    //use Model\PublicApi;
    //

    //
    static $__adamant = true; 
}