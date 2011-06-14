<?php

use Application\Bender\Bender;
abstract class BaseTest extends PHPUnit_Framework_TestCase
{


	protected function setUp()
	{
		define('APPLICATION_PATH', realpath("."));
		require_once 'Application/Bender/Bender.php';

		Bender::getInstance()->registerAutoloader();
	}

}


