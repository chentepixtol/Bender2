<?php

namespace Test;

use Application\Database\Cast\AbstractCast;

use Application\Database\ColumnCollection;

use Application\Database\Column;

use Application\Database\ForeignKey;

use Application\Config\Configuration;

use Application\Database\Table;

use Application\Database\Database;

require_once 'Test/BaseTest.php';

use Application\Native\String;

class DatabaseTest extends BaseTest
{

    public function setUp(){
        $this->getBender()->loadContainer('Test/config/Services.xml');
    }

    /**
     *
     * @test
     */
    public function connection()
    {
        try {
            $this->getBender()->getConnection()->connect();
        } catch (\Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
        $this->assertTrue(true);
    }

    /**
     *
     * @test
     * @depends connection
     */
    public function database()
    {
        $database = $this->getBender()->getDatabase();
        $this->assertTrue($database instanceof Database);
    }

    /**
     *
     * @test
     * @depends database
     */
    public function tableCollection()
    {
        $database = $this->getBender()->getDatabase();
        $tableCollection = $database->getTables();

        $this->assertTrue($tableCollection->containsIndex('Person'));
        $this->assertTrue($tableCollection->containsIndex('Post'));
        $this->assertTrue($tableCollection->containsIndex('User'));
        $this->assertTrue($tableCollection->containsIndex('Worker'));

        $this->assertEquals(4, $tableCollection->count());

        $personTable = $tableCollection->getByTablename('bender_persons');
        $postTable = $tableCollection->getByTablename('bender_posts');
        $userTable = $tableCollection->getByTablename('bender_users');
        $workerTable = $tableCollection->getByTablename('bender_workers');

        $this->assertTrue($personTable instanceof Table);
        $this->assertTrue($postTable instanceof Table);
        $this->assertTrue($userTable instanceof Table);
        $this->assertTrue($workerTable instanceof Table);
    }

    /**
     *
     * @test
     * @depends tableCollection
     */
    public function personTable()
    {
        $database = $this->getBender()->getDatabase();
        $personTable = $database->getTables()->getByPK('Person');

        $this->assertTrue($personTable instanceof Table);
        $this->assertTrue($personTable->getDatabase() === $database);
        $this->assertTrue($personTable->hasPrimaryKey());
        $this->assertFalse($personTable->hasParent());
        $this->assertNull($personTable->getParent());
        $this->assertEquals('bender_persons', $personTable->getName()->toString());
        $this->assertEquals('Person', $personTable->getObject()->toString());

        $this->checkConfiguration($personTable);
    }

    /**
     *
     * @test
     * @depends tableCollection
     */
    public function userTable()
    {
        $database = $this->getBender()->getDatabase();
        $userTable = $database->getTables()->getByPK('User');
        $personTable = $database->getTables()->getByPK('Person');

        $this->assertTrue($userTable instanceof Table);
        $this->assertTrue($userTable->getDatabase() === $database);
        $this->assertTrue($userTable->hasParent());
        $this->assertTrue($personTable->hasPrimaryKey());
        $this->assertTrue( $personTable === $userTable->getParent() );
        $this->assertEquals('bender_users', $userTable->getName()->toString());
        $this->assertEquals('User', $userTable->getObject()->toString());

        $this->checkConfiguration($userTable);
    }

    /**
     *
     * @test
     * @depends tableCollection
     */
    public function foreignKeys()
    {
        $database = $this->getBender()->getDatabase();
        $userTable = $database->getTables()->getByPK('User');
        $personTable = $database->getTables()->getByPK('Person');

        $foreignKey = $userTable->getForeignKeys()->getOne();

        $this->assertTrue($foreignKey instanceof ForeignKey);
        $this->assertTrue( $foreignKey->getLocalTable() === $userTable);
        $this->assertTrue( $foreignKey->getForeignTable() === $personTable);
        $this->assertTrue( $foreignKey->getLocal() instanceof Column);
        $this->assertTrue( $foreignKey->getForeign() instanceof Column);
    }

    /**
     *
     * @test
     * @depends tableCollection
     */
    public function columnCollection()
    {
        $database = $this->getBender()->getDatabase();
        $userTable = $database->getTables()->getByPK('User');

        $columns = $userTable->getColumns();

        $this->assertTrue($columns instanceof ColumnCollection);
        $this->assertTrue($columns->containsIndex('id_person'));
    }

    /**
     *
     * @test
     * @depends columnCollection
     */
    public function column()
    {
        $database = $this->getBender()->getDatabase();
        $userTable = $database->getTables()->getByPK('User');
        $columns = $userTable->getColumns();

        $columnIdPerson = $columns->getByPK('id_person');
        $columnIdUser = $columns->getByPK('id_user');

        $this->assertTrue($columnIdPerson instanceof Column);
        $this->assertTrue($columnIdPerson->getTable() === $userTable);
        $this->assertTrue($columnIdPerson->getName() instanceof String);
        $this->assertTrue($columnIdUser->isPrimaryKey());
        $this->assertTrue($userTable->hasPrimaryKey());
        $this->assertTrue($columnIdUser == $userTable->getPrimaryKey());
        $this->assertTrue($columnIdUser->isUnique());
        $this->assertTrue($columnIdPerson->isUnique());
        $this->assertEquals('idPerson', $columnIdPerson->getName()->toCamelCase());
        $this->assertEquals('id_person', "$columnIdPerson");
        $this->assertEquals('getIdPerson', $columnIdPerson->getter());
        $this->assertEquals('setIdPerson', $columnIdPerson->setter());
    }

    /**
     *
     * @test
     * @depends column
     */
    public function columnDatatype()
    {
        $database = $this->getBender()->getDatabase();
        $userTable = $database->getTables()->getByPK('User');

        $idUser = $userTable->getColumns()->getByPK('id_user');
        $username = $userTable->getColumns()->getByPK('username');

        $this->assertTrue($idUser->getAutoincrement());
        $this->assertTrue($idUser->getNotnull());
        $this->assertEquals('integer', $idUser->getType());

        $salary = $database->getTables()->getByPK('Worker')->getColumns()->getByPK('salary');
        $idWorker = $database->getTables()->getByPK('Worker')->getColumns()->getByPK('id_worker');

        $this->assertEquals('float', $salary->getType());
        $this->assertEquals('1000', $salary->getDefault());
        $this->assertFalse($salary->getNotnull());
    }


    /**
     *
     * @param Table $table
     */
    private function checkConfiguration($table)
    {
        $configuration = $table->getConfiguration();
        $options = $table->getOptions();
        $this->assertTrue($configuration instanceof Configuration);
        $this->assertTrue($options instanceof Configuration);
    }


}


