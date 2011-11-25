<?php

namespace Tests;

use Tests\Mock\BSingleton;
use Tests\Mock\ASingleton;

require_once 'Tests/BaseTest.php';

class SingletonTest extends BaseTest
{

	/**
	 *
	 * @test
	 */
	public function main()
	{
		$a = ASingleton::getInstance();
		$b = BSingleton::getInstance();

		$this->assertTrue($a instanceof ASingleton);
		$this->assertTrue($b instanceof BSingleton);

		$a2 = ASingleton::getInstance();

		$this->assertTrue($a === $a2);
		$this->assertTrue($a !== $b);
	}

}


