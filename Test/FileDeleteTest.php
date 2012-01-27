<?php

namespace Test;

require_once 'Test/BaseTest.php';

use Application\File\Delete;
use Application\Generator\File\Writer;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;

/**
 *
 * @author chente
 *
 */
class FileDeleteTest extends BaseTest
{

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('root'));

        $fileWriter = new Writer('ISO 8859-1');
        $fileWriter->save(vfsStream::url('root/dir1/file1.txt'), "Content");
        $fileWriter->save(vfsStream::url('root/dir1/file2.txt'), "Content");
        $fileWriter->save(vfsStream::url('root/dir2/sub/file1.txt'), "Content");
        $fileWriter->save(vfsStream::url('root/dir2/sub/file2.txt'), "Content");
    }

    /**
     *
     * @test
     */
    public function deleteDirectory()
    {
        $delete = new Delete();
        $delete->addPath(vfsStream::url('root/dir1'));
        $delete->exec();

        $root = vfsStreamWrapper::getRoot();
        $this->assertFalse($root->hasChild('dir1'));
    }

}


