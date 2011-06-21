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
	public function set($parameter, $value)
	{
		if( is_array($value) ){
			$this->parameters[$parameter] = new Configuration($value);
		}
		else{
			$this->parameters[$parameter] = $value;
		}

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
	 * @param mixed $path
	 */
	public function getByPath($path)
	{
		$parts = explode('.', $path);

		$find = $parts[0];
		unset($parts[0]);

		if( $this->has($find) ){
			$children = $this->get($find);
			if( count($parts) ){
				if( $children instanceof Configuration ){
					$path = implode('.', $parts);
					return $children->getByPath($path);
				}else{
					return null;
				}
			}
			else{
				return $children;
			}
		}
		return null;
	}

	/**
	 *
	 * @param string $parameter
	 */
	public function has($parameter){
		return isset($this->parameters[$parameter]);
	}

	/**
	 *
	 * @return array
	 */
	public function toArray()
	{
		$array = array();
		foreach ($this->parameters as $index => $parameter){
			$array[$index] = $parameter instanceof Configuration ? $parameter->toArray() : $parameter;
		}
		return $array;
	}

	/**
	 *
	 * @return array
	 */
	public function getParameters(){
		return $this->parameters;
	}

}