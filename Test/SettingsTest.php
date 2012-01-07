<?php

namespace Test;

use Symfony\Component\Yaml\Yaml;

require_once 'Test/BaseTest.php';
require_once 'vfsStream/vfsStream.php';

use Application\Config\Settings;
use Application\Generator\File\Writer;
use vfsStream, vfsStreamWrapper, vfsStreamDirectory;

class SettingsTest extends BaseTest
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
    public function getEncoding(){
        return 'UTF-8';
    }

    /**
     *
     * @return string
     */
    public function getOutputDir(){
        return 'output';
    }

    /**
     *
     * @return array
     */
    public function getConnectionParams(){
        return array(
            'dbname' => 'bender',
            'user' => 'bender',
            'password' => 123,
            'host:' => 'localhost',
            'driver' => 'pdo_mysql',
        );
    }

    /**
     *
     * @return array
     */
    public function getOptions(){
        return array('author' => 'chente');
    }

    /**
     *
     * @return array
     */
    public function getAlias(){
        return array('schema' => $this->getAliasSchema());
    }

    /**
     *
     * @return array
     */
    public function getAliasSchema(){
        return array(
            'project' => 'Core',
            'modules' => array('Schema')
        );
    }

    /**
     *
     * @return array
     */
    public function getSettingsArray(){
        return array(
            'settings' => array(
                'encoding' => $this->getEncoding(),
                'output_dir' => $this->getOutputDir(),
                'db' => $this->getConnectionParams(),
                'options' => $this->getOptions(),
                'alias' => $this->getAlias(),
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

        $this->filename = vfsStream::url('root/config/settings.yml');
        $fileWriter = new Writer();

        $fileWriter->save($this->filename, Yaml::dump($this->getSettingsArray()));
    }

    /**
     *
     * @test
     */
    public function constructor()
    {
        $settings = new Settings($this->filename);
    }

    /**
     *
     * @test
     * @expectedException InvalidArgumentException
     */
    public function fileNotExists(){
        $settings = new Settings(vfsStream::url('root/config/notFound.yml'));
    }

    /**
     *
     * @test
     */
    public function getters()
    {
        $settings = new Settings($this->filename);

        $this->assertEquals($this->getConnectionParams(), $settings->getConnectionParams()->toArray());
        $this->assertEquals($this->getEncoding(), $settings->getEnconding());
        $this->assertEquals($this->getOptions(), $settings->getOptions()->toArray());
        $this->assertEquals($this->getOutputDir(), $settings->getOutputDir());

    }

    /**
     *
     * @test
     */
    public function alias()
    {
        $settings = new Settings($this->filename);
        $this->assertTrue($settings->hasAlias('schema'));
        $this->assertEquals($this->getAliasSchema(), $settings->getAlias('schema')->toArray());
        $this->assertFalse($settings->hasAlias('notFound'));
    }

    /**
     *
     * @test
     */
    public function defaultOption(){
        $settings = new Settings($this->filename);
        $this->assertNull($settings->get('notExists', null));
    }


}


