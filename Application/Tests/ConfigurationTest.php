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
     */
    public function getParameters(){
    	$params = array('particula' => array('beta', 'gamma'));
    	$configuration = new Configuration($params);
    	$parameters = $configuration->getParameters();
    	$this->assertTrue(isset($parameters['particula']));
    	$this->assertTrue($parameters['particula'] instanceof Configuration);
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

    /**
     *
     * @test
     */
    public function path(){
    	$configuration = new Configuration(array(
    		'a1' => array(
    			'b1' => array(
    				'c1'=>'value'
    			), 'b2' => array(
    				'c1' => 'value2',
    			)
    		)
    	));
    	$this->assertEquals('value', $configuration->getByPath('a1.b1.c1'));
    	$this->assertNull($configuration->getByPath('l99'));
    	$this->assertEquals(array('c1' => 'value'), $configuration->getByPath('a1.b1')->toArray());
    	$this->assertNull($configuration->getByPath('a1.b2.c1.z4'));
    	$this->assertEquals(array(
    			'b1' => array(
    				'c1'=>'value'
    			), 'b2' => array(
    				'c1' => 'value2',
    			)
    		), $configuration->getByPath('a1')->toArray());
    }

}


