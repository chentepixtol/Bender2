<?php
namespace Application\Config;

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
	 * @var array
	 */
	protected $parameters = array();

	/**
	 *
	 *
	 * @param array $parameters
	 */
	public function __construct($parameters = array())
	{
		if( !is_array($parameters) ){
			throw new \Exception("Parametros no validos ");
		}

		foreach ($parameters as $parameter => $value) {
			$this->set($parameter, $value);
		}

	}

	/**
	 *
	 *
	 * @param string $parameter
	 * @param mixed $value
	 * @return Application\Bender\Configuration
	 */
	public function set($parameter, $value){
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
	public function get($parameter, $default = null){
		return isset($this->parameters[$parameter]) ?  $this->parameters[$parameter] : $default;
	}

	/**
	 *
	 * @param string $parameter
	 */
	public function has($parameter){
		return isset($this->parameters[$parameter]);
	}

}