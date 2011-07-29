<?php
namespace Application\Generator;

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
	 * @var string
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
	 *
	 * @param string $name
	 * @param string $route
	 * @param string $namespace
	 */
	public function __construct($name, $route, $namespace)
	{
		$this->setName($name);
		$this->setRoute($route);
		$this->setNamespace($namespace);
	}

	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 *
	 * @return string
	 */
	public function getNamespacedName(){
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

}