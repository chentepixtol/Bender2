<?php

namespace Application\Tests;

use Application\Config\Configuration;

use Application\Database\Table;

use Application\Database\Database;

require_once 'Application/Tests/BaseTest.php';

use Application\Native\String;

class DatabaseTest extends BaseTest
{

	public function setUp(){
		$this->getBender()->loadContainer('Application/Tests/config/Services.xml');
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
		$this->assertFalse($personTable->hasParent());
		$this->assertNull($personTable->getParent());
		$this->assertEquals('bender_persons', $personTable->getName()->toString());
		$this->assertEquals('Person', $personTable->getObject()->toString());

		$this->checkConfiguration($personTable);
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


