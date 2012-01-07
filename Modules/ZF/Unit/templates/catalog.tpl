{% include 'header.tpl' %}
{% set BaseTest = classes.get('BaseTest') %}

namespace Test\Unit;
{{ BaseTest.printRequire(true) }}

{{ Catalog.printUse() }}

class {{ Catalog }}Test extends {{ BaseTest }}
{

    /**
     * @test
     */
    public function singleton()
    {
        $this->assertTrue({{ Catalog }}::getInstance() instanceOf {{ Catalog }});
    }

}
