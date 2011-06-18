<?php

namespace Application\Tests;

use Application\Tests\Mock\BSingleton;
use Application\Tests\Mock\ASingleton;

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


