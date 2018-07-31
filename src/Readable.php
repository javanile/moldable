<?php
/**
 * Trait with utility methods to handle errors.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable;

class Readable implements Notations
{
    use Model\LoadApi;
    use Model\ReadApi;
    use Model\JoinApi;
    use Model\UtilApi;
    use Model\DebugApi;
    use Model\FetchApi;
    use Model\ClassApi;
    use Model\ModelApi;
    use Model\TableApi;
    use Model\QueryApi;
    use Model\FieldApi;
    use Model\PublicApi;
    use Model\SchemaApi;
    use Model\FilterApi;
    use Model\RawApi;

    /**
     * @var type
     */
    public static $__config = [
        'adamant'                => true,
        'table-name-conventions' => null,
        'error-mode'             => 'fatal',
    ];

    public function __construct()
    {
    }
}
