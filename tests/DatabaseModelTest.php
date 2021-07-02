<?php

namespace Javanile\Moldable\Tests;

use Javanile\Moldable\Database;
use PHPUnit\Framework\TestCase;

final class DatabaseModelTest extends TestCase
{
    use DatabaseTrait;

    public function testDatabaseModelAll()
    {
        $db = $this->getDatabaseInstance();

        $db->apply(['model' => [
            'name' => '',
            'hook' => 0,
        ]]);

        $db->submit('model', [
            'name' => 'Frank',
            'hook' => 10,
        ]);

        $db->submit('model', [
            'name' => 'Karl',
            'hook' => 12,
        ]);

        $db->submit('model', [
            'name' => 'Andy',
            'hook' => 11,
        ]);

        $results = $db->all('model', ['order' => 'name']);

        $this->assertEquals([
            0 => [
                'name' => 'Andy',
                'hook' => 11,
            ],
            1 => [
                'name' => 'Frank',
                'hook' => 10,
            ],
            2 => [
                'name' => 'Karl',
                'hook' => 12,
            ],
        ], $results);
    }

    public function testDatabaseModelSubmit()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        //
        $db->apply(['model' => [
            'name' => '',
            'hook' => 0,
        ]]);
        $count = $db->getValue('SELECT COUNT(*) FROM model');
        $this->assertEquals($count, 0);

        //
        $db->submit('model', [
            'name' => 'Frank',
            'hook' => 10,
        ]);
        $count = $db->getValue('SELECT COUNT(*) FROM model');
        $this->assertEquals($count, 1);

        //
        $db->submit('model', [
            'name' => 'Frank',
            'hook' => 10,
        ]);
        $count = $db->getValue('SELECT COUNT(*) FROM model');
        $this->assertEquals($count, 1);

        //
        $db->submit('model', [
            'name' => 'Frank',
            'hook' => 11,
        ]);
        $count = $db->getValue('SELECT COUNT(*) FROM model');
        $this->assertEquals($count, 2);
    }

    public function testDatabaseModelExists()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        //
        $db->apply(['model' => [
            'name' => '',
            'hook' => 0,
        ]]);
        $db->submit('model', [
            'name' => 'Frank',
            'hook' => 10,
        ]);

        $exists = $db->exists('model', ['name' => 'Tony']);
        $this->assertEquals($exists, false);

        $exists = $db->exists('model', ['hook' => 10]);
        $this->assertEquals($exists, [
            'name' => 'Frank',
            'hook' => 10,
        ]);

        $exists = $db->exists('model', ['where' => "name LIKE 'Frank'"]);
        $this->assertEquals($exists, [
            'name' => 'Frank',
            'hook' => 10,
        ]);
    }

    public function testDatabaseModelImport()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $db->import('model', []);

        $db->import('model', [
            ['name' => 'Frank', 'hook' => 10],
            ['name' => 'Andy', 'hook' => 12],
            ['name' => 'Mike', 'hook' => 14],
        ]);

        $exists = $db->exists('model', ['name' => 'Tony']);
        $this->assertEquals($exists, false);

        $exists = $db->exists('model', ['hook' => 10]);
        $this->assertEquals($exists, [
            'name' => 'Frank',
            'hook' => 10,
        ]);
    }

    public function testDatabaseModelDrop()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $db->apply('animal', 'field0');

        $db->import('model', [
            ['name' => 'Frank', 'hook' => 10],
            ['name' => 'Andy', 'hook' => 12],
            ['name' => 'Mike', 'hook' => 14],
        ]);

        $db->drop('model', 'not confirm');
        $models = $db->getModels();
        $this->assertEquals($models, ['animal', 'model']);

        $db->drop('model', 'confirm');

        $schema = $db->desc();

        $this->assertEquals($schema, [
            'animal' => [
                'field0' => [
                    'Field'   => 'field0',
                    'Type'    => 'varchar(255)',
                    'Null'    => 'YES',
                    'Key'     => '',
                    'Default' => null,
                    'Extra'   => '',
                    'First'   => true,
                    'Before'  => false,
                ],
            ],
        ]);
    }

    public function testDatabaseModelFieldApi()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $db->apply([
            'albums' => [
                'title'  => '',
                'author' => '',
            ],
        ]);

        $mainField = $db->getPrimaryKeyOrMainField('albums');

        $this->assertEquals($mainField, 'title');
    }

    public function testDatabaseGetModelsApi()
    {
        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $db->apply([
            'albums' => [
                'title'  => '',
                'author' => '',
            ],
        ]);

        $models = $db->getModels();

        $this->assertEquals($models, ['albums']);
    }

    public function testDump()
    {
        $this->expectOutputRegex('/^<pre.+pre>$/s');

        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $db->apply([
            'albums' => [
                'title'  => '',
                'author' => '',
            ],
        ]);

        $db->dump();
    }

    public function testDump2()
    {
        $this->expectOutputRegex('/^<pre.+pre>$/s');

        $db = new Database([
            'host'     => $GLOBALS['DB_HOST'],
            'port'     => $GLOBALS['DB_PORT'],
            'dbname'   => $GLOBALS['DB_NAME'],
            'username' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASS'],
        ]);

        $db->apply([
            'albums' => [
                'title'  => '',
                'author' => '',
            ],
        ]);

        $db->dump('albums');
    }
}
