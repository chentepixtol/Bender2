<?php

namespace Test;

require_once 'Test/BaseTest.php';

use Application\Generator\File\Writer;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;

class FileWriterTest extends BaseTest
{

    public function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('root'));
    }

    /**
     *
     * @test
     */
    public function save()
    {
        $fileWriter = new Writer('ISO 8859-1');
        $fileWriter->save(vfsStream::url('root/subdirectory/test.txt'), "Content");


        $this->assertTrue(vfsStreamWrapper::getRoot()->getChild('subdirectory')->hasChild('test.txt'));
        $this->assertEquals("Content", vfsStreamWrapper::getRoot()->getChild('subdirectory')->getChild('test.txt')->getContent());
    }


    /**
     *
     * @test
     */
    public function overwrite()
    {
        $fileWriter = new Writer('ISO 8859-1', 'UTF-8', false);
        $fileWriter->save(vfsStream::url('root/files/MyFile.txt'), "First Content");
        $fileWriter->save(vfsStream::url('root/files/MyFile.txt'), "Second Content");

        $this->assertTrue(vfsStreamWrapper::getRoot()->getChild('files')->hasChild('MyFile.txt'));
        $this->assertEquals("First Content", vfsStreamWrapper::getRoot()->getChild('files')->getChild('MyFile.txt')->getContent());
    }


}


