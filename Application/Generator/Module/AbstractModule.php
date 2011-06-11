<?php

namespace Application\Generator\Module;

use Application\Bender\Event\EmptyEventSubscriber;
use Application\Bender\View;
use Application\Bender\Bender;
use Application\Generator\File\FileCollection;
use Application\Database\TableCollection;

abstract class AbstractModule implements Module
{
	/**
	 *
	 * @var Application\Bender\View
	 */
	protected $view;

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.Module::init()
	 */
	public function init(){
		$this->view =  $this->getBender()->getView($this);
	}

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
			$this->getModulePath(). '/templates/',
			$this->getModulesPath().'/templates/',
		);
	}

	/**
	 *
	 * @return string
	 */
	public function getModulePath()
	{
		$path = preg_match('$\/([a-z\/]+)\/$i', str_replace('\\', '/', get_class($this)), $matches) ? $matches[1] : $this->getName();
		return $this->getBender()->getConfiguration()->get('modulesPath').$path;
	}

	/**
	 *
	 * @return string
	 */
	public function getModulesPath()
	{
		$path = preg_match('$\/([a-z]+)\/$i', str_replace('\\', '/', get_class($this)), $matches) ? $matches[1] : $this->getName();
		return $this->getBender()->getConfiguration()->get('modulesPath'). $path;
	}

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.Module::getSubscriber()
	 */
	public function getSubscriber(){
		return new EmptyEventSubscriber();
	}

}