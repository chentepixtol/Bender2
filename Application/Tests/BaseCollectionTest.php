<?php

namespace Application\Tests;

use Application\Base\BaseCollection;

use Application\Tests\Mock\Item;
use Application\Tests\Mock\MyCollection;

require_once 'Application/Tests/BaseTest.php';


class BaseCollectionTest extends BaseTest
{

	/**
	 *
	 * @test
	 */
	public function initialization()
	{
		$myCollection = new MyCollection();
		$this->checkEmpty($myCollection);
	}

	/**
	 * (non-PHPdoc)
	 * @see PHPUnit_Framework_Assert::assertEmpty()
	 */
	protected function checkEmpty($myCollection)
	{
		$this->assertEquals(0, $myCollection->count());
		$this->assertFalse($myCollection->valid());
		$this->assertTrue($myCollection->isEmpty());
		$this->assertEquals(array(), $myCollection->getPrimaryKeys());
		$this->assertEquals(array(), $myCollection->getArrayCopy());

		$itemNull = $myCollection->getOne();
		$this->assertNull($itemNull);
	}

	/**
	 *
	 * @test
	 */
	public function append()
	{
		$myCollection = new MyCollection();

		$item1 = new Item(1, 'apple');
		$item2 = new Item(2, 'orange');
		$item3 = new Item(3, 'banana');
		$item4 = new Item(4, 'apple');

		$myCollection->append($item3);
		$item3Ref = $myCollection->getByPK($item3->getId());

		$this->assertTrue($myCollection->containsIndex($item3->getId()));
		$this->assertTrue($item3 === $item3Ref);
		$this->assertEquals(1, $myCollection->count());
		$this->assertFalse($myCollection->isEmpty());
		$this->assertTrue($myCollection->valid());

		$myCollection->append($item3);
		$this->assertEquals(1, $myCollection->count());

		$myCollection->append($item2);
		$this->assertEquals(2, $myCollection->count());
		$this->assertEquals(array(3,2), $myCollection->getPrimaryKeys());
	}

	/**
	 *
	 * @test
	 */
	public function remove()
	{
		$myCollection = new MyCollection();

		$item1 = new Item(1, 'apple');

		$myCollection->append($item1);
		$myCollection->remove($item1->getId());

		$this->checkEmpty($myCollection);

		$itemNull = $myCollection->getByPK(999);
		$this->assertNull($itemNull);
	}

	/**
	 *
	 * @test
	 */
	public function cycle()
	{
		$myCollection = new MyCollection();

		$item2 = new Item(2, 'orange');
		$item3 = new Item(3, 'banana');

		$myCollection->append($item2);
		$myCollection->append($item3);

		while ($myCollection->valid()) {
			$item = $myCollection->read();
			$this->assertTrue($item instanceof Item);
		}

		$this->assertFalse($myCollection->valid());
		$myCollection->rewind();
		$this->assertTrue($myCollection->valid());

		$first = $myCollection->getOne();
		$this->assertTrue($first instanceof Item);
		$this->assertEquals($item2->getId(), $first->getId());

	}

	/**
	 *
	 * @test
	 */
	public function merge()
	{
		$myCollection1 = new MyCollection();
		$myCollection2 = new MyCollection();

		$item1 = new Item(1, 'apple');
		$item2 = new Item(2, 'orange');
		$item3 = new Item(3, 'banana');

		$myCollection1->append($item1);
		$myCollection1->append($item3);

		$myCollection2->append($item2);
		$myCollection2->append($item3);

		$myCollection1->merge($myCollection2);

		$this->assertEquals(3 ,$myCollection1->count());
		$this->assertEquals(array(1,3,2), $myCollection1->getPrimaryKeys());
	}

	/**
	 *
	 * @test
	 */
	public function intersect()
	{
		$myCollection1 = new MyCollection();
		$myCollection2 = new MyCollection();

		$item1 = new Item(1, 'apple');
		$item2 = new Item(2, 'orange');
		$item3 = new Item(3, 'banana');

		$myCollection1->append($item1);
		$myCollection1->append($item3);

		$myCollection2->append($item2);
		$myCollection2->append($item3);

		$newCollection = $myCollection1->intersect($myCollection2);

		$this->assertEquals(1 ,$newCollection->count());
		$this->assertTrue($newCollection->containsIndex($item3->getId()));
		$this->assertEquals(array(3), $newCollection->getPrimaryKeys());
	}

	/**
	 *
	 * @test
	 */
	public function diff()
	{
		$myCollection1 = new MyCollection();
		$myCollection2 = new MyCollection();

		$item1 = new Item(1, 'apple');
		$item2 = new Item(2, 'orange');
		$item3 = new Item(3, 'banana');

		$myCollection1->append($item1);
		$myCollection1->append($item3);

		$myCollection2->append($item2);
		$myCollection2->append($item3);

		$myCollection1->diff($myCollection2);

		$this->assertEquals(1, $myCollection1->count());
		$this->assertFalse($myCollection1->containsIndex($item3->getId()));
		$this->assertTrue($myCollection1->containsIndex($item1->getId()));
		$this->assertEquals(array(1), $myCollection1->getPrimaryKeys());
	}

	/**
	 *
	 * @test
	 */
	public function each()
	{
		$myCollection = new MyCollection();

		$item1 = new Item(1, 'apple');
		$item2 = new Item(2, 'orange');
		$item3 = new Item(3, 'banana');
		$item4 = new Item(4, 'beach');

		$myCollection->append($item1);
		$myCollection->append($item2);
		$myCollection->append($item3);
		$myCollection->append($item4);

		$withA = array();
		$myCollection->each(function (Item $item) use (&$withA){
			if( preg_match('/^a/', $item->getValue()) ){
				$withA[] = $item->getValue();
			}
		});

		$this->assertEquals(array('apple'), $withA);
	}

	/**
	 *
	 * @test
	 */
	public function filter()
	{
		$myCollection = new MyCollection();

		$item1 = new Item(1, 'apple');
		$item2 = new Item(2, 'orange');
		$item3 = new Item(3, 'banana');
		$item4 = new Item(4, 'beach');

		$myCollection->append($item1);
		$myCollection->append($item2);
		$myCollection->append($item3);
		$myCollection->append($item4);

		$myCollectionWithO = $myCollection->filter(function (Item $item){
			return preg_match('/o/i', $item->getValue());
		});

		$this->assertTrue($myCollectionWithO instanceof MyCollection);
		$this->assertEquals(1, $myCollectionWithO->count());
		$this->assertTrue($myCollectionWithO->containsIndex(2));
		$this->assertEquals($item2, $myCollectionWithO->read());
	}

	/**
	 *
	 * @test
	 * @expectedException Exception
	 */
	public function validate()
	{
		$myCollection = new MyCollection();
		$myCollection->append(new \stdClass());
	}

}