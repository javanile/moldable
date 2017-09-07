<?php
/**
 * Socket trait comunications and interactions with database.
 *
 * PHP version 5.6
 *
 * @author Francesco Bianco
 */

namespace Javanile\Moldable\Database\Socket;

interface SocketInterface
{
    /**
     * Get a single record.
     *
     * @param mixed      $sql
     * @param null|mixed $params
     */
    public function getRow($sql, $params = null);
}
