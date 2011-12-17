<?php

namespace Test;

use Application\Event\Event;

require_once 'Test/BaseTest.php';

class EventTest extends BaseTest
{

	/**
	 * @test
	 */
	public function main(){
		$parametes = array(
			'number1' => 'one',
			'number2' => 'two',
		);
		$event = new Event($parametes);
		$this->assertEquals('one', $event->get('number1'));
		$this->assertEquals('two', $event->get('number2'));
		$this->assertEquals('three', $event->get('number3', 'three'));
		$this->assertEquals($parametes, $event->getParameters());
	}

}


