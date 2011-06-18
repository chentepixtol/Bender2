<?php
namespace Application\Tests;

use Application\Bender\Bender;

/**
 *
 * @author chente
 *
 */
abstract class BaseTest extends \PHPUnit_Framework_TestCase
{

	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $name
	 * @param array $data
	 * @param unknown_type $dataName
	 */
	public function __construct($name = NULL, array $data = array(), $dataName = ''){
		parent::__construct($name,$data,$dataName);
		$this->registerAutoloader();
	}

	/**
	 *
	 * register Autoloader
	 */
	public function registerAutoloader()
	{
		if( !defined('APPLICATION_PATH') ){
			define('APPLICATION_PATH', realpath("."));
			require_once 'Application/Bender/Bender.php';

			Bender::getInstance()->registerAutoloader();
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	protected function setUp()
	{

	}

}
