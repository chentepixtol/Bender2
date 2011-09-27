{% include 'header.tpl' %}
{% set BaseTest = classes.get('BaseTest') %}

namespace Test\Unit;
{{ BaseTest.printRequire(true) }}

{{ Factory.printUse() }}

class {{ Factory }}Test extends {{ BaseTest }}
{

	/**
	 * @test
	 */
	public function createFromArray()
	{
		$anValue = 'An value to the Factory';
		$array = array(
{%for field in fields %}
        	'{{ field }}' => $anValue,
{% endfor %}
		);

		${{ bean }} = {{ Factory }}::createFromArray($array);
		$this->assertTrue(${{ bean }} instanceof \{{ Bean.getFullname() }});

{%for field in fields %}
       	$this->assertEquals($array['{{ field }}'], ${{ bean }}->{{ field.getter }}());
{% endfor %}
	}

}
