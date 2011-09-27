{% include 'header.tpl' %}
{% set BaseTest = classes.get('BaseTest') %}
{% set BaseBean = classes.get('Bean') %}
{% set Collectable = classes.get('Collectable') %}

namespace Test\Unit;
{{ BaseTest.printRequire(true) }}

{{ Bean.printUse() }}

class {{ Bean }}Test extends {{ BaseTest }}
{

	/**
	 * @test
	 */
	public function interfaces()
	{
		${{ bean }} = new {{ Bean }}();

		$this->assertTrue(${{ bean }} instanceof \{{ BaseBean.getFullName() }});
		$this->assertTrue(${{ bean }} instanceof \{{ Collectable.getFullName() }});
	}

	/**
	 * @test
	 */
	public function settersAndGetters()
	{
		${{ bean }} = new {{ Bean }}();

		$anValue = 'An value to the bean';

{%for field in fields %}
        ${{ bean }}->{{ field.setter }}($anValue);
        $this->assertEquals($anValue, ${{ bean }}->{{ field.getter }}());

{% endfor %}
	}

	/**
	 * @test
	 */
	public function getIndex()
	{
		${{ bean }} = new {{ Bean }}();

		$pk = '123';
		${{ bean }}->{{ primaryKey.setter }}($pk);
		$this->assertEquals($pk, ${{ bean }}->getIndex());

	}

	/**
	 * @test
	 */
	public function toArray()
	{
		${{ bean }} = new {{ Bean }}();
		$anValue = 'An value to the bean';

{%for field in fields %}
        ${{ bean }}->{{ field.setter }}($anValue);
{% endfor %}

		$array = array(
{%for field in fields %}
        	'{{ field }}' => $anValue,
{% endfor %}
		);

		$this->assertEquals($array, array_filter(${{ bean }}->toArray()));

	}

}
