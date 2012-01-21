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
        $this->assertEquals("Content", vfsStreamWrapper::getRoot()->getChild('subdirectory')->getChild('test.txt')->getContent("Content"));
    }


}


