<?php

namespace Application\Bender;

use Application\Generator\Module\Module;

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
	 *
	 * @var \Twig_Environment
	 */
	protected $current;

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
		if( !array_key_exists($index, $this->twigs) ){
			$loader = new \Twig_Loader_Filesystem($directories);
			$this->twigs[$index] = new \Twig_Environment($loader, array());
			$create = true;
		}

		$this->current = $this->twigs[$index];

		return $create;
	}

	/**
	 *
	 *
	 */
	public function clear(){
		$this->locals = array();
	}


}

