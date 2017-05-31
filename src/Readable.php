<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */
namespace Javanile\Moldable;

use Javanile\Moldable\Notations;

class Readable implements Notations
{
    use Model\LoadApi;
    use Model\FetchApi;
    use Model\ErrorApi;
    use Model\ClassApi;
    use Model\ModelApi;
    use Model\TableApi;
    use Model\FieldApi;
    use Model\PublicApi;
    use Model\SchemaApi;
    use Model\DatabaseApi;
    #use Model\LoadApi;
    #use Model\ReadApi;
    #use Model\JoinApi;
    #use Model\FieldApi;
    #use Model\FetchApi;
    #use Model\PublicApi;
    #use Model\DebugApi;
    //
    //use Model\ErrorApi;
    //
    //
    //use Model\UpdateApi;
    //
    //use Model\PublicApi;
    //

    /**
     *
     * @var type
     */
    static $__config = [
        'adamant' => true,
        'table-name-conventions' => null,
    ];

    /**
     *
     */
    public function __construct()
    {

    }
}