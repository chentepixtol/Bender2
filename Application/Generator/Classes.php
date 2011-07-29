<?php
namespace Application\Generator;

use Application\Config\Configuration;

/**
 *
 * @author chente
 *
 */
class Classes
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
	 * @param BaseClass $class
	 * @return Application\Generator\Classes
	 */
	public function add($name, BaseClass $class)
	{
		if( is_object($name) && method_exists($name,'__toString') ){
			$this->configuration->set($name->__toString(), $class);
		}else{
			$this->configuration->set($name, $class);
		}
		return $this;
	}

	/**
	 *
	 * @return Application\Generator\BaseClass
	 */
	public function get($name)
	{
		if( is_object($name) && method_exists($name,'__toString') ){
			return $this->configuration->get($name->__toString());
		}else{
			return $this->configuration->get($name);
		}
	}

}