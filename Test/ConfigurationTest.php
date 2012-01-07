<?php

namespace Test;

require_once 'Test/BaseTest.php';

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
     * @expectedException InvalidArgumentException
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
    public function countItems(){
        $configuration = new Configuration();

        $this->assertEquals(0, $configuration->count());
        $this->assertTrue($configuration->isEmpty());

        $configuration->set('pi', '3.1416');
        $this->assertEquals(1, $configuration->count());
        $this->assertFalse($configuration->isEmpty());

        $configuration->set('myArray', array('a', 'b', 'c'));
        $this->assertEquals(2, $configuration->count());
        $this->assertEquals(3, $configuration->get('myArray')->count());

        $this->assertEquals(4, $configuration->countTotal());
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
        $this->assertEquals('value', $configuration->getByDotNotation('a1.b1.c1'));
        $this->assertNull($configuration->getByDotNotation('l99'));
        $this->assertEquals(array('c1' => 'value'), $configuration->getByDotNotation('a1.b1')->toArray());
        $this->assertNull($configuration->getByDotNotation('a1.b2.c1.z4'));
        $this->assertEquals(array(
                'b1' => array(
                    'c1'=>'value'
                ), 'b2' => array(
                    'c1' => 'value2',
                )
            ), $configuration->getByDotNotation('a1')->toArray());
    }

}


