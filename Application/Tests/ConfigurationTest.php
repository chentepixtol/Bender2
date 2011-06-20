<?php

namespace Application\Tests;

require_once 'Application/Tests/BaseTest.php';

use Application\Config\Configuration;

class ConfigurationTest extends BaseTest
{

	/**
	 *
	 * @test
	 */
    public function main()
    {
    	$params = array('particula' => 'beta');
    	$configuration = new Configuration($params);
    	$configuration->set('pi', 3.1416);

    	$this->assertEquals(3.1416, $configuration->get('pi'));
    	$this->assertTrue($configuration->has('pi'));
    	$this->assertTrue($configuration->has('particula'));
    	$this->assertEquals('beta', $configuration->get('particula'));
    	$this->assertFalse($configuration->has('notExistent'));
    	$this->assertEquals('default', $configuration->get('notExistent', 'default'));
    }

    /**
     *
     * @test
     */
    public function toArray(){
    	$params = array('particula' => 'beta');
    	$configuration = new Configuration($params);
    	$configuration->set('pi', 3.1416);
    	$this->assertEquals(array('particula' => 'beta', 'pi' => 3.1416), $configuration->toArray());
    }

    /**
     *
     * @test
     * @expectedException Exception
     */
    public function notArray()
    {
    	$stdClass = new \stdClass();
    	$configuration = new Configuration($stdClass);
    }

}


