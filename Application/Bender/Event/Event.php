<?php

namespace Application\Bender\Event;

use Symfony\Component\EventDispatcher\Event as sfEvent;

/**
 *
 *
 * @author chente
 *
 */
class Event extends sfEvent
{

	/**
	 * Events
	 */
	const DATABASE_BEFORE_INSPECT = 'database.before_inspect';
	const DATABASE_AFTER_INSPECT = 'database.after_inspect';
	const DATABASE_BEFORE_CONFIGURE = 'database.before_configure';
	const DATABASE_AFTER_CONFIGURE = 'database.after_configure';
	const VIEW_INIT = 'view.init';
	const VIEW_MODULE_CREATE = 'view.module_create';
	const CLI_READY = 'cli.ready';
	const CONNECTION_ESTABILISHED = 'connection.estabilished';
	const LOAD_MODULES = 'load.modules';

	/**
	 *
	 *
	 * @var array
	 */
	protected $parameters = array();

	/**
	 *
	 * @param array $array
	 */
	public function __construct($parameters = array() )
	{
		if( !is_array($parameters) )
			throw new \Exception("Parameters no permitidos");

		foreach ($parameters as $parameter => $value){
			$this->setParameter($parameter, $value);
		}
	}

	/**
	 *
	 *
	 * @param string $parameter
	 * @param mixed $value
	 */
	public function setParameter($parameter, $value){
		$this->parameters[$parameter] = $value;
	}

	/**
	 *
	 *
	 * @param string $paremeter
	 * @param mixed $default
	 * @return mixed
	 */
	public function getParameter($paremeter, $default = null){
		return isset($this->parameters[$paremeter]) ? $this->parameters[$paremeter] : $default;
	}

	/**
	 *
	 * @return array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

}
