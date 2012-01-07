<?php

namespace Application\Generator\File;

/**
 *
 *
 * @author chente
 *
 */
class Writer
{

    /**
     *
     *
     * @var string
     */
    protected $encoding;

    /**
     *
     *
     * @param string $encoding
     */
    public function __construct($encoding = 'UTF-8'){
        $this->encoding = $encoding;
    }

    /**
     *
     *
     * @param string $filename
     * @param string $content
     */
    public function save($filename, $content)
    {
        $dir = dirname($filename);
        if (!is_dir($dir)){
            mkdir($dir, 0777, true);
        }

        $handle = fopen($filename, "w+");
          if ( $this->encoding != 'UTF-8' ){
              $content = iconv("UTF-8", $this->encoding, $content);
          }

          fwrite($handle, $content);
          fclose($handle);
    }

}