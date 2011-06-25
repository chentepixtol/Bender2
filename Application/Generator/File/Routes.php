<?php
namespace Application\Generator\File;

use Application\Config\Configuration;

/**
 *
 * @author chente
 *
 */
class Routes
{

	/**
	 *
	 * @var Application\Config\Configuration
	 */
	protected $configuration;

	/**
	 *
	 */
	public function __construct(){
		$this->configuration = new Configuration();
	}

	/**
	 *
	 * @param string $name
	 * @param string $route
	 */
	public function addRoute($name, $route){
		$this->configuration->set($name, $route);
	}

	/**
	 *
	 * @param string $route
	 */
	public function getRoute($route){
		return $this->configuration->get($route);
	}

}