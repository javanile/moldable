<?php
/**
 * Class that handle a connection with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Parser\Pgsql;

trait CommonTrait
{
    private static function getNotationAspectsJson(
        $notation,
        $aspects
    ) {
        // decode json object into notation
        $json = json_decode(trim($notation, '<>'), true);

        // override default with json passed
        if (is_array($json)) {
            foreach ($json as $key => $value) {
                $aspects[$key] = $value;
            }
        }

        return $aspects;
    }

    protected function getNotationAspectsSchema($notation, $aspects)
    {
        // override default notation schema passed
        foreach ($notation as $key => $value) {
            $aspects[$key] = $value;
        }

        return $aspects;
    }
}
