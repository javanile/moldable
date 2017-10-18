<?php
/**
 * Collect API to handle fields of a model.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Database;

trait RawApi
{
    /*\
    > Il modo in cui usi i trait è sbagliato. Un trait non dovrebbe mai
    > dipendere da altri trait o da funzionalità di una classe base. Dentro la
    > cartella Model, per esempio, nessuno dei trait può essere usato
    > indipendentemente dagli altri. Tanto vale fare una classe unica, o usare
    > composizione di classi, e non "finta ereditarietà multipla".
    > (Anonimo)
    \*/

    /**
     * Retrieve primary key name of specific model.
     *
     * @param type  $model
     * @param mixed $sql
     *
     * @return type
     */
    public function raw($sql)
    {
        $results = $this->getResults($sql);

        return $results;
    }
}
