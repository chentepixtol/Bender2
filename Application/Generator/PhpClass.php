<?php
namespace Application\Generator;

/**
 *
 * @author chente
 *
 */
class PhpClass extends BaseClass
{

	/**
	 *
	 *
	 * @var unknown_type
	 */
	protected $separatorNamespace = "\\";

	/**
	 * @param string $route
	 */
	public function __construct($route)
	{
		preg_match('/\/([a-z0-9]{1,})\.php$/i', $route, $matches);
		$name = $matches[1];
		$namespace = str_replace(array('/'.$name.'.php'), '', $route);
		$namespace = str_replace('/', '\\', $namespace);
		parent::__construct($name, $route, $namespace);
	}

}