<?php
namespace Tests;

use Application\Bender\Bender;

require_once 'autoload.php';

/**
 *
 * @author chente
 *
 */
abstract class BaseTest extends \PHPUnit_Framework_TestCase
{

	/**
	 *
	 * @return Application\Bender\Bender
	 */
	public function getBender()
	{
		require_once 'Application/Bender/Bender.php';
		return Bender::getInstance();
	}

}

