<?php
namespace Application\Generator\Module;

use Application\Bender\Bender;
use Application\Generator\File\FileCollection;
use Application\Database\TableCollection;

abstract class AbstractModule implements Module
{

	/**
	 *
	 * @return Application\Bender\Bender
	 */
	public function getBender(){
		return Bender::getInstance();
	}

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.Module::getTemplateDirs()
	 */
	public function getTemplateDirs(){
		return array(
			$this->getModulePath().'/templates/',
			$this->getModulesPath().'templates/',
		);
	}

	/**
	 *
	 * @return string
	 */
	public function getModulePath()
	{
		$path = preg_match('$\/([a-z]+)\/$i', str_replace('\\', '/', get_class($this)), $matches) ? $matches[1] : $this->getName();
		return $this->getModulesPath().'/'.$path;
	}

	/**
	 *
	 * @return string
	 */
	public function getModulesPath()
	{
		return $this->getBender()->getConfiguration()->getParameter('modulesPath');
	}

}