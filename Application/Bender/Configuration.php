<?php
namespace Application\Bender;

/**
 *
 *
 * @author chente
 *
 */
class Configuration
{
	/**
	 *
	 *
	 * @var unknown_type
	 */
	protected $parameters = array();

	/**
	 *
	 *
	 * @param string $parameter
	 * @param mixed $value
	 * @return Application\Bender\Configuration
	 */
	public function setParameter($parameter, $value){
		$this->parameters[$parameter] = $value;
		return $this;
	}

	/**
	 *
	 *
	 * @param string $parameter
	 * @param mixed $default
	 * @return mixed
	 */
	public function getParameter($parameter, $default = null){
		return isset($this->parameters[$parameter]) ?  $this->parameters[$parameter] : $default;
	}

}