<?php

namespace Application\File;

/**
 *
 * @author chente
 *
 */
class Delete
{

    /**
     *
     * @var array
     */
    private $paths = array();

    /**
     *
     * @param string $path
     */
    public function addPath($path){
        $this->paths[] = $path;
    }

    /**
     *
     */
    public function exec()
    {
        $mode = \RecursiveIteratorIterator::CHILD_FIRST;
        foreach( $this->paths as $path )
        {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), $mode);
            foreach ($iterator as $fileInfo){
                if( $fileInfo->isFile() ){
                    unlink($fileInfo->getPathname());
                }
            }

            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), $mode);
            foreach( $iterator as $fileInfo ){
                if( $fileInfo->isDir() && !in_array($fileInfo->getBasename(), array('.', '..')) ){
                    rmdir($fileInfo->getPathname());
                }
            }

            if( is_dir($path) ){
                rmdir($path);
            }
        }

    }


}