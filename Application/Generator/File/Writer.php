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
     * @var string
     */
    protected $encoding;

    /**
     *
     * @var string
     */
    protected $encodingContent;

    /**
     *
     * @param string $encoding
     * @param string $encodingContent
     */
    public function __construct($encoding = 'UTF-8', $encodingContent = 'UTF-8'){
        $this->encoding = $encoding;
        $this->encodingContent = $encodingContent;
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
        if( !is_dir($dir) ){
            mkdir($dir, 0777, true);
        }

        $handle = fopen($filename, "w+");
        if ( $this->encoding != $this->encodingContent ){
            $content = iconv($this->encodingContent, $this->encoding, $content);
        }

        fwrite($handle, $content);
        fclose($handle);
    }

}