<?php

namespace Javanile\Moldable\Tests\Model;

use Javanile\Moldable\Database;
use Javanile\Moldable\Readable;
use Javanile\Moldable\Storable;
use Javanile\Moldable\Tests\DefaultDatabaseTrait;
use Javanile\Moldable\Tests\Sample\AllNotations;
use Javanile\Producer;
use PHPUnit\Framework\TestCase;

Producer::addPsr4(['Javanile\\Moldable\\Tests\\' => __DIR__.'/../']);

final class NotationApiTest extends TestCase
{
    use DefaultDatabaseTrait;

    public function testAllNotationsApi()
    {
        $object = new AllNotations();

        $this->assertEquals(true, $object->booleanTrue);
        $this->assertEquals(false, $object->booleanFalse);
        $this->assertEquals('Hello World!', $object->string);
        $this->assertEquals('', $object->varchar);
        $this->assertEquals('', $object->text);
        $this->assertEquals(3.14, $object->float);
        $this->assertEquals(null, $object->enumWithNull);
        $this->assertEquals('A', $object->enum);
        //$this->assertEquals('00:00:00', $object->time);
        //$this->assertEquals('0000-00-00', $object->date);
        //$this->assertEquals('0000-00-00 00:00:00', $object->datetime);

        //Producer::log($this->time);
    }
}
