{% include 'header.tpl' %}
{% set BaseTest = classes.get('BaseTest') %}
{% set BaseCollectionTest = classes.get('BaseCollectionTest') %}
{% set Collection = classes.get('Collection') %}
{% set Collectable = classes.get('Collectable') %}

namespace Test\Unit;
{{ BaseTest.printRequire(true) }}

class MyCollection extends \{{ Collection.getFullname() }}{}

class MyBean implements \{{ Collectable.getFullname() }}
{
	private $id;
	private $value;

	public function __construct($id, $value){
		$this->setId($id);
		$this->setValue($value);
	}

	public function getIndex(){
		return $this->getId();
	}

	public function getId() {
		return $this->id;
	}

	public function getValue() {
		return $this->value;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setValue($value) {
		$this->value = $value;
	}

	public function toArray(){
		return array('id' => $this->id, 'value' => $this->value);
	}
}



class {{ BaseCollectionTest }} extends {{ BaseTest }}
{

	/**
	 *
	 * @dataProviders
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

		$myBeanNull = $myCollection->getOne();
		$this->assertNull($myBeanNull);
	}

	/**
	 *
	 * @test
	 */
	public function append()
	{
		$myCollection = new MyCollection();

		$myBean1 = new MyBean(1, 'apple');
		$myBean2 = new MyBean(2, 'orange');
		$myBean3 = new MyBean(3, 'banana');
		$myBean4 = new MyBean(4, 'apple');

		$myCollection->append($myBean3);
		$myBean3Ref = $myCollection->getByPK($myBean3->getId());

		$this->assertTrue($myCollection->containsIndex($myBean3->getId()));
		$this->assertTrue($myBean3 === $myBean3Ref);
		$this->assertEquals(1, $myCollection->count());
		$this->assertFalse($myCollection->isEmpty());
		$this->assertTrue($myCollection->valid());

		$myCollection->append($myBean3);
		$this->assertEquals(1, $myCollection->count());

		$myCollection->append($myBean2);
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

		$myBean1 = new MyBean(1, 'apple');

		$myCollection->append($myBean1);
		$myCollection->remove($myBean1->getId());

		$this->checkEmpty($myCollection);

		$myBeanNull = $myCollection->getByPK(999);
		$this->assertNull($myBeanNull);
	}

	/**
	 *
	 * @test
	 */
	public function cycle()
	{
		$myCollection = new MyCollection();

		$myBean2 = new MyBean(2, 'orange');
		$myBean3 = new MyBean(3, 'banana');

		$myCollection->append($myBean2);
		$myCollection->append($myBean3);

		while ($myCollection->valid()) {
			$myBean = $myCollection->read();
			$this->assertTrue($myBean instanceof MyBean);
		}

		$this->assertFalse($myCollection->valid());
		$myCollection->rewind();
		$this->assertTrue($myCollection->valid());

		$first = $myCollection->getOne();
		$this->assertTrue($first instanceof MyBean);
		$this->assertEquals($myBean2->getId(), $first->getId());

	}

	/**
	 *
	 * @test
	 */
	public function merge()
	{
		$myCollection1 = new MyCollection();
		$myCollection2 = new MyCollection();

		$myBean1 = new MyBean(1, 'apple');
		$myBean2 = new MyBean(2, 'orange');
		$myBean3 = new MyBean(3, 'banana');

		$myCollection1->append($myBean1);
		$myCollection1->append($myBean3);

		$myCollection2->append($myBean2);
		$myCollection2->append($myBean3);

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

		$myBean1 = new MyBean(1, 'apple');
		$myBean2 = new MyBean(2, 'orange');
		$myBean3 = new MyBean(3, 'banana');

		$myCollection1->append($myBean1);
		$myCollection1->append($myBean3);

		$myCollection2->append($myBean2);
		$myCollection2->append($myBean3);

		$newCollection = $myCollection1->intersect($myCollection2);

		$this->assertEquals(1 ,$newCollection->count());
		$this->assertTrue($newCollection->containsIndex($myBean3->getId()));
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

		$myBean1 = new MyBean(1, 'apple');
		$myBean2 = new MyBean(2, 'orange');
		$myBean3 = new MyBean(3, 'banana');

		$myCollection1->append($myBean1);
		$myCollection1->append($myBean3);

		$myCollection2->append($myBean2);
		$myCollection2->append($myBean3);

		$myCollection1->diff($myCollection2);

		$this->assertEquals(1, $myCollection1->count());
		$this->assertFalse($myCollection1->containsIndex($myBean3->getId()));
		$this->assertTrue($myCollection1->containsIndex($myBean1->getId()));
		$this->assertEquals(array(1), $myCollection1->getPrimaryKeys());
	}

	/**
	 *
	 * @test
	 */
	public function each()
	{
		$myCollection = new MyCollection();

		$myBean1 = new MyBean(1, 'apple');
		$myBean2 = new MyBean(2, 'orange');
		$myBean3 = new MyBean(3, 'banana');
		$myBean4 = new MyBean(4, 'beach');

		$myCollection->append($myBean1);
		$myCollection->append($myBean2);
		$myCollection->append($myBean3);
		$myCollection->append($myBean4);

		$withA = array();
		$myCollection->each(function (MyBean $myBean) use (&$withA){
			if( preg_match('/^a/', $myBean->getValue()) ){
				$withA[] = $myBean->getValue();
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

		$myBean1 = new MyBean(1, 'apple');
		$myBean2 = new MyBean(2, 'orange');
		$myBean3 = new MyBean(3, 'banana');
		$myBean4 = new MyBean(4, 'beach');

		$myCollection->append($myBean1);
		$myCollection->append($myBean2);
		$myCollection->append($myBean3);
		$myCollection->append($myBean4);

		$myCollectionWithO = $myCollection->filter(function (MyBean $myBean){
			return preg_match('/o/i', $myBean->getValue());
		});

		$this->assertTrue($myCollectionWithO instanceof MyCollection);
		$this->assertEquals(1, $myCollectionWithO->count());
		$this->assertTrue($myCollectionWithO->containsIndex(2));
		$this->assertEquals($myBean2, $myCollectionWithO->read());
	}

	/**
	 *
	 * @test
	 */
	public function mapAndToArray()
	{
		$myCollection = new MyCollection();

		$myBean1 = new MyBean(1, 'apple');
		$myBean2 = new MyBean(2, 'orange');
		$myBean3 = new MyBean(3, 'banana');

		$myCollection->append($myBean1);
		$myCollection->append($myBean2);
		$myCollection->append($myBean3);

		$array = $myCollection->map(function (MyBean $myBean){
			return $myBean->getId() .'-'. $myBean->getValue();
		});

		$this->assertEquals(array('1-apple', '2-orange', '3-banana'), $array);
		$this->assertEquals(array(
			1 => $myBean1->toArray(),
			2 => $myBean2->toArray(),
			3 => $myBean3->toArray()
		), $myCollection->toArray());
	}

	/**
	 *
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function invalidCallbackForMap()
	{
		$myCollection = new MyCollection();

		$array = $myCollection->map(new \stdClass);
	}

	/**
	 *
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function invalidCallbackForEach()
	{
		$myCollection = new MyCollection();

		$array = $myCollection->each(new \stdClass);
	}

	/**
	 *
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function validate()
	{
		$myCollection = new MyCollection();
		$myCollection->append(new \stdClass());
	}

}