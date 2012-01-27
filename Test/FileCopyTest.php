<?php

namespace Test;

require_once 'Test/BaseTest.php';

use Application\File\Copy;
use Application\Generator\File\Writer;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;

/**
 *
 * @author chente
 *
 */
class FileCopyTest extends BaseTest
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
    public function copyFiles()
    {
        $root = vfsStreamWrapper::getRoot();
        $copy = new Copy(new Writer('ISO 8859-1', 'ISO 8859-1'));
        $copy->addPath(vfsStream::url('root/dir1/file1.txt'), vfsStream::url('root/dirA/fileA.txt'));
        $copy->exec();
        $this->assertTrue($root->hasChild('dirA'));
        $this->assertTrue($root->getChild('dirA')->hasChild('fileA.txt'));
        $this->assertEquals('Content', $root->getChild('dirA')->getChild('fileA.txt')->getContent());
    }

    /**
     *
     * @test
     */
    public function copyDirectories()
    {
        $root = vfsStreamWrapper::getRoot();
        $copy = new Copy(new Writer('ISO 8859-1', 'ISO 8859-1'));
        $copy->addPath(vfsStream::url('root/dir1'), vfsStream::url('root/dirA'));
        $copy->exec();
        $this->assertTrue($root->hasChild('dirA'));
        $this->assertTrue($root->getChild('dirA')->hasChild('file1.txt'));
        $this->assertEquals('Content', $root->getChild('dirA')->getChild('file1.txt')->getContent());

        $this->assertTrue($root->getChild('dirA')->hasChild('file2.txt'));
        $this->assertEquals('Content', $root->getChild('dirA')->getChild('file2.txt')->getContent());
    }

    /**
     * @test
     */
    public function overwrite()
    {
        $fileWriter = new Writer('ISO 8859-1');
        $fileWriter->save(vfsStream::url('root/directory/main/file1.txt'), "Directory One Content");
        $fileWriter->save(vfsStream::url('root/directory2/main/file1.txt'), "Directory Two Content");

        $copy = new Copy(new Writer('ISO 8859-1', 'ISO 8859-1', false));
        $copy->addPath(vfsStream::url('root/directory'), vfsStream::url('root/directory2'));
        $copy->exec();

        $root = vfsStreamWrapper::getRoot();
        $contentFileDirectory2 = $root->getChild('directory2')->getChild('main')->getChild('file1.txt')->getContent();
        $this->assertEquals("Directory Two Content", $contentFileDirectory2);
    }

}


