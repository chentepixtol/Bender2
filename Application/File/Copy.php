<?php

namespace Application\File;


use Application\Event\Event;

use Symfony\Component\EventDispatcher\EventDispatcher;

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
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     *
     * @param Writer
     */
    public function __construct(Writer $fileWriter){
        $this->fileWriter = $fileWriter;
    }

    /**
     * @param EventDispatcher $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcher $eventDispatcher){
        $this->eventDispatcher = $eventDispatcher;
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
            $source = $path;
            $destination = $this->to[$i];
            if( is_file($source) ){
                $this->fileWriter->save($destination, file_get_contents($source));
                $this->triggerEvents($source, $destination);
            }elseif( is_dir($path) ){
                $this->createCopyDirectory($source, $destination);
            }else{
                throw new \Exception("No existe el directorio {$source}");
            }
        }
    }

    /**
     *
     * @param string $source
     * @param string $destination
     */
    private function triggerEvents($source, $destination){
        if( $this->eventDispatcher ){
            if( md5_file($destination) != md5_file($source) ){
                $this->eventDispatcher->dispatch('copy.skip_file', new Event(array(
                    'source' => $source,
                    'destination' => $destination,
                )));
            }
        }
    }

    /**
     *
     * @param unknown_type $source
     * @param unknown_type $destination
     */
    protected function createCopyDirectory($sourceDirectory, $destinationDirectory)
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($sourceDirectory));
        foreach($iterator as $path){
            /* @var $path \SplFileInfo */
            if( !$path->isDir() ) {

                $dir = str_replace($sourceDirectory, $destinationDirectory, $path->getPath()) . DIRECTORY_SEPARATOR;
                $destination = $dir . $path->getFilename();
                $source = $path->getPathname();

                $this->fileWriter->save($destination, file_get_contents($source));
                $this->triggerEvents($source, $destination);
            }
        }
    }

}