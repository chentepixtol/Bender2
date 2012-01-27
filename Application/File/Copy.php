<?php

namespace Application\File;


use Application\Generator\File\Writer;

/**
 *
 * @author chente
 *
 */
class Copy
{
    /**
     *
     * @var array
     */
    private $from = array();

    /**
     *
     * @var array
     */
    private $to = array();

    /**
     *
     * @var Writer
     */
    private $fileWriter;

    /**
     *
     * @param Writer
     */
    public function __construct(Writer $fileWriter){
        $this->fileWriter = $fileWriter;
    }

    /**
     *
     * @param string $from
     * @param string $to
     */
    public function addPath($from, $to){
        $this->from[] = $from;
        $this->to[] = $to;
    }

    /**
     *
     */
    public function exec(){
        foreach ($this->from as $i => $path){
            if( is_file($path) ){
                $this->fileWriter->save($this->to[$i], file_get_contents($path));
            }elseif( is_dir($path) ){
                $this->createCopyDirectory($path, $this->to[$i]);
            }else{
                throw new \Exception("No existe el directorio {$path}");
            }
        }
    }

    /**
     *
     * @param unknown_type $source
     * @param unknown_type $destination
     */
    protected function createCopyDirectory($source, $destination)
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source));
        foreach($iterator as $path){
            /* @var $path \SplFileInfo */
            if( !$path->isDir() ) {
                $dir = str_replace($source, $destination, $path->getPath()) . DIRECTORY_SEPARATOR;
                $this->fileWriter->save($dir . $path->getFilename(), file_get_contents($path->getPathname()));
            }
        }
    }

}