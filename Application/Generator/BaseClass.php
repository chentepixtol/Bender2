<?php

namespace Application\Generator;

use Application\Native\String;

/**
 *
 * @author chente
 *
 */
class BaseClass
{

	/**
	 *
	 *
	 * @var String
	 */
	protected $name;

	/**
	 *
	 *
	 * @var string
	 */
	protected $route;

	/**
	 *
	 *
	 * @var string
	 */
	protected $namespace;

	/**
	 *
	 *
	 * @var string
	 */
	protected $separatorNamespace = ".";

	/**
	 *
	 * @var boolean
	 */
	protected static $addIncludes = true;

	/**
	 *
	 *
	 * @param string $name
	 * @param string $route
	 * @param string $namespace
	 */
	public function __construct($name, $route, $namespace)
	{
		$this->setName(new String($name, String::UPPERCAMELCASE));
		$this->setRoute($route);
		$this->setNamespace($namespace);
	}

	/**
	 * @return Application\Native\String
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString(){
		return $this->toString();
	}

	/**
	 *
	 * @return string
	 */
	public function toString(){
		return $this->getName()->toString();
	}

	/**
	 *
	 * @return string
	 */
	public function getFullName(){
		return $this->getNamespace().$this->separatorNamespace.$this->getName();
	}

	/**
	 * @return the $route
	 */
	public function getRoute() {
		return $this->route;
	}

	/**
	 * @return the $namespace
	 */
	public function getNamespace() {
		return $this->namespace;
	}

	/**
	 * @param field_type $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param field_type $route
	 */
	public function setRoute($route) {
		$this->route = $route;
	}

	/**
	 * @param field_type $namespace
	 */
	public function setNamespace($namespace) {
		$this->namespace = $namespace;
	}

	/**
	 *
	 * @param boolean $bool
	 */
	public static function addIncludes($bool){
		self::$addIncludes = $bool;
	}

}