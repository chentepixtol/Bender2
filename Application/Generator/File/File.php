<?php
namespace Application\Generator\File;

/**
 *
 * @author chente
 *
 */
class File
{

	/**
	 *
	 *
	 * @var string
	 */
	protected $fullpath;

	/**
	 *
	 *
	 * @var string
	 */
	protected $content;

	/**
	 *
	 *
	 * @param string $fullpath
	 * @param string $content
	 */
	public function __construct($fullpath, $content){
		$this->setFullpath($fullpath);
		$this->setContent($content);
	}

	/**
	 * @return string
	 */
	public function getFullpath() {
		return $this->fullpath;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param string $fullpath
	 */
	public function setFullpath($fullpath) {
		$this->fullpath = $fullpath;
	}

	/**
	 * @param string $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}




}