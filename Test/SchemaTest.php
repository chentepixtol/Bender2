<?php

namespace Test;

use vfsStream, vfsStreamWrapper, vfsStreamDirectory;
use Symfony\Component\Yaml\Yaml;
use Application\Config\Schema;
use Application\Generator\File\Writer;

require_once 'Test/BaseTest.php';
require_once 'vfsStream/vfsStream.php';


class SchemaTest extends BaseTest
{

    /**
     *
     * @var string
     */
    protected $filename;

    /**
     *
     * @return string
     */
    public function getPersonArray(){
        return array('tablename' => 'bender_persons');
    }

    /**
     *
     * @return string
     */
    public function getPostArray(){
        return array(
            'tablename' => 'bender_posts',
            'options' => array( 'crud' => true )
        );
    }

    /**
     *
     * @return array
     */
    public function getUserArray(){
        return array(
            'tablename' => 'bender_users',
            'extends' => 'Person'
        );
    }

    /**
     *
     * @return array
     */
    public function getWorkerArray(){
        return array(
            'tablename' => 'bender_workers',
            'extends' => 'Person'
        );
    }

    /**
     *
     * @return array
     */
    public function getSchemaArray(){
        return array(
            'schema' => array(
                'Person' => $this->getPersonArray(),
                'Post' => $this->getPostArray(),
                'User' => $this->getUserArray(),
                'Worker' => $this->getWorkerArray(),
            )
        );
    }

    /**
     * (non-PHPdoc)
     * @see Test.BaseTest::setUp()
     */
    public function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('root'));

        $this->filename = vfsStream::url('root/config/schema.yml');
        $fileWriter = new Writer();

        $fileWriter->save($this->filename, Yaml::dump($this->getSchemaArray()));
    }

    /**
     *
     * @test
     */
    public function constructor()
    {
        $schema = new Schema($this->filename);
    }

    /**
     *
     * @test
     * @expectedException InvalidArgumentException
     */
    public function fileNotExists(){
        $schema = new Schema(vfsStream::url('root/config/notFound.yml'));
    }

    /**
     *
     * @test
     */
    public function person()
    {
        $schema = new Schema($this->filename);

        $configuration = $schema->createConfiguration('bender_persons');
        $this->assertEquals('Person', $configuration->get('object')->toString());
        $this->assertEquals(array(), $configuration->get('options')->toArray());
        $this->assertFalse($configuration->get('extends'));
    }

    /**
     *
     * @test
     */
    public function post()
    {
        $schema = new Schema($this->filename);

        $configuration = $schema->createConfiguration('bender_posts');
        $this->assertEquals('Post', $configuration->get('object')->toString());
        $this->assertEquals(array('crud' => true), $configuration->get('options')->toArray());
        $this->assertFalse($configuration->get('extends'));
    }

    /**
     *
     * @test
     */
    public function user()
    {
        $schema = new Schema($this->filename);

        $configuration = $schema->createConfiguration('bender_users');
        $this->assertEquals('User', $configuration->get('object')->toString());
        $this->assertEquals(array(), $configuration->get('options')->toArray());
        $this->assertEquals('Person', $configuration->get('extends'));
    }

    /**
     *
     * @test
     */
    public function worker()
    {
        $schema = new Schema($this->filename);

        $configuration = $schema->createConfiguration('bender_workers');
        $this->assertEquals('Worker', $configuration->get('object')->toString());
        $this->assertEquals(array(), $configuration->get('options')->toArray());
        $this->assertEquals('Person', $configuration->get('extends'));
    }

}


