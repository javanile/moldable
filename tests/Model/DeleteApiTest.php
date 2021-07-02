<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Moldable\Database;
use Javanile\Moldable\Tests\DatabaseTrait;
use Javanile\Moldable\Tests\DefaultDatabaseTrait;
use Javanile\Moldable\Tests\Sample\People;
use PHPUnit\Framework\TestCase;

final class DeleteApiTest extends TestCase
{
    use DefaultDatabaseTrait;

    public function testDeleteApi()
    {
        $id = People::insert([
            'name'    => 'Frank',
            'surname' => 'White',
            'age'     => 18,
        ])->id;

        $table = $this->db->getWriter()->quote('prefix_People');
        $sql = "SELECT age FROM {$table} WHERE id = {$id}";
        $this->assertEquals(18, $this->db->getValue($sql));

        People::delete($id);
        $this->assertEquals(null, $this->db->getValue($sql));
    }
}
