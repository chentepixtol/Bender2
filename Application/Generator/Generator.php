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
		$this->modules->rewind();
		while ( $this->modules->valid() ) {
			$module = $this->modules->read();
			$files = $module->getFiles();
			while ( $files->valid() ) {
				$file = $files->read();
				echo $file->getFullpath() . " => \n" . $file->getContent() ."\n";
			}
		}
		$this->modules->rewind();
	}

	/**
	 *
	 *
	 * @param Module $module
	 */
	public function addModule(Module $module){
		$this->modules->append($module);
	}

	/**
	 *
	 * @return Application\Generator\Module\ModuleCollection
	 */
	public function getModules(){
		$this->modules->rewind();
		return $this->modules;
	}

	/**
	 *
	 * @param Application\Generator\Module\ModuleCollection $modules
	 */
	public function setModules(ModuleCollection $modules){
		$this->modules = $modules;
	}
}

