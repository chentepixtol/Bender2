<?php

namespace Test;

use Test\Mock\BSingleton;
use Test\Mock\ASingleton;

require_once 'Test/BaseTest.php';

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


