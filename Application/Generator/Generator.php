<?php

namespace Application\Generator;

use Application\Generator\Module\ModuleCollection;
use Application\Generator\Module\Module;

/**
 *
 * @author chente
 *
 */
class Generator
{
	/**
	 *
	 * @var Application\Generator\Module\ModuleCollection
	 */
	protected $modules;

	/**
	 *
	 *
	 */
	public function __construct(){
		$this->modules = new ModuleCollection();
	}

	/**
	 *
	 *
	 * @param Module $module
	 */
	public function generate()
	{
		while ( $this->modules->valid() ) {
			$module = $this->modules->read();
			$files = $module->getFiles();
			while ( $files->valid() ) {
				$file = $files->read();
				echo $file->getFullpath() . " => \n" . $file->getContent() ."\n";
			}
		}
	}

	/**
	 *
	 *
	 * @param Module $module
	 */
	public function addModule(Module $module){
		$this->modules->append($module);
	}
}

