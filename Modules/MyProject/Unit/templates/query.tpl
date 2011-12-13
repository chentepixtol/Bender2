{% include 'header.tpl' %}
{% set BaseTest = classes.get('BaseTest') %}

namespace Test\Unit;

{{ Query.printUse() }}

{{ BaseTest.printRequire(true) }}
{{ Query.printRequire() }}

class {{ Bean }}QueryTest extends {{ BaseTest }}
{

    /**
     * @test
      */
    public function create(){
        $query = {{ Query }}::create();
        $this->assertTrue($query instanceof {{ Query }});
    }

    /**
     * @test
      */
    public function initialization(){
        $query = {{ Query }}::create();
        $defaultColumn = array('{{ Bean }}.*');
        
{% set auxTable = table %}
{% for i in 1..5 %}
{% if auxTable.hasParent() %}
{% set auxTable = auxTable.getParent() %}
        $this->assertTrue($query->hasJoin('{{ auxTable.getObject().toUpperCamelCase() }}'));
        $defaultColumn[] = '{{ auxTable.getObject().toUpperCamelCase() }}.*';
{% endif%}
{% endfor %}
        $this->assertTrue($query->getDefaultColumn() == $defaultColumn);
    }


}
