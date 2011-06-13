<?php

namespace Application\Generator;



use Application\Bender\Bender;
use Application\Event\Event;
use Application\Generator\Module\ModuleCollection;
use Application\Generator\Module\Module;
use Application\Generator\File\Writer;

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
	 * @var Application\Generator\File\Writer
	 */
	protected $writer;

	/**
	 *
	 *
	 */
	public function __construct(){
		$this->modules = new ModuleCollection();
		$encoding = $this->getBender()->getSettings()->getEnconding();
		$this->writer = new Writer($encoding);
	}

	/**
	 *
	 *
	 * @param Module $module
	 */
	public function generate()
	{
		$this->modules->rewind();
		while ( $this->modules->valid() )
		{
			$module = $this->modules->read();
			$module->init();

			$files = $module->getFiles();
			$this->getBender()->dispatch(Event::SAVE_FILES, new Event(array(
				'module' => $module,
			)));

			while ( $files->valid() )
			{
				$file = $files->read();

				$filename = $this->getBender()->getConfiguration()->get('project', 'default') .'/'. $file->getFullpath();
				$fullpath = APPLICATION_PATH .'/'. $this->getBender()->getSettings()->getOutputDir() .'/' . $filename;

				$this->writer->save($fullpath, $file->getContent());
				$this->getBender()->dispatch(Event::SAVE_FILE, new Event(array(
					'module' => $module,
					'fullpath' => $fullpath,
					'filename' => $filename,
				)));
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

	/**
	 *
	 * @return Application\Bender\Bender
	 */
	public function getBender(){
		return Bender::getInstance();
	}
}

