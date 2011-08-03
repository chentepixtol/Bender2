<?php

namespace Application\Bender;

use Application\Generator\Module\Module;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Application\Event\Event;

/**
 *
 * @author chente
 *
 */
class View
{

	/**
	 * @var array
	 */
	protected $twigs = array();

	/**
	 * @var array
	 */
	protected $globals = array();

	/**
	 * @var array
	 */
	protected $locals = array();

	/**
	 *
	 * @var Symfony\Component\EventDispatcher\EventDispatcher
	 */
	protected $eventDispatcher;

	/**
	 *
	 *
	 * @var \Twig_Environment
	 */
	protected $current;

	/**
	 *
	 * @var string
	 */
	protected $currentIndex;

	/**
	 *
	 * @param EventDispatcher $eventDispatcher
	 */
	public function __construct(EventDispatcher $eventDispatcher){
		$this->eventDispatcher = $eventDispatcher;
		$this->eventDispatcher->dispatch(Event::VIEW_INIT, new Event(array('view' => $this)));
	}

	/**
	 *
	 * @param string $param
	 * @param mixed $value
	 */
	public function __set($param, $value){
		$this->locals[$param] = $value;
	}

	/**
	 *
	 *
	 * @param strinf $param
	 * @param mixed $value
	 */
	public function addGlobal($param, $value){
		$this->globals[$param] = $value;
	}

	/**
	 *
	 * @param string $tpl
	 */
	public function fetch($tpl){
		$template = $this->current->loadTemplate($tpl);
		return $template->render(array_merge($this->globals, $this->locals));
	}

	/**
	 *
	 *
	 * @param string
	 * @return boolean
	 */
	public function toggleToDirectory($directories)
	{
		$create = false;

		$index = is_array($directories) ? implode(',', $directories) : $directories;
		if( $this->currentIndex == $index ){
			return false;
		}

		if( !array_key_exists($index, $this->twigs) ){
			$loader = new \Twig_Loader_Filesystem($directories);
			$this->twigs[$index] = new \Twig_Environment($loader, array('autoescape' => false));
			$this->currentIndex = $index;
			$create = true;
		}
		$this->current = $this->twigs[$index];

		return $create;
	}

	/**
	 *
	 * @param Module $module
	 */
	public function toggleToModule(Module $module)
	{
		if( $this->toggleToDirectory($module->getTemplateDirs()) ){
			$this->eventDispatcher->dispatch(Event::VIEW_MODULE_CREATE, new Event(
				array('view' => $this, 'module' => $module)
			));
		}
	}

	/**
	 *
	 *
	 */
	public function clear(){
		$this->locals = array();
	}


}

