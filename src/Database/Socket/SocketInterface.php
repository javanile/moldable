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
     *
     */
    public function getRow($sql, $params=null);
}