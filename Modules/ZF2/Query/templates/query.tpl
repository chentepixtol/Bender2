{% include 'header.tpl' %}
{% set BaseQuery = classes.get('BaseQuery') %}

{{ Query.printNamespace() }}

{{ Catalog.printUse() }}
{{ Bean.printUse() }}

{{ Catalog.printRequire() }}
{{ Bean.printRequire() }}

{% set auxTable = table %}
{% for i in 1..5 %}
{% if auxTable.hasParent() %}
{% set auxTable = auxTable.getParent() %}
{{ classes.get(auxTable.getObject).printUse() }}
{{ classes.get(auxTable.getObject).printRequire() }}
{% endif%}
{% endfor %}

{% if parent %}
    {% set parentQuery = classes.get(parent.getObject()~'Query') %}
{% else %}
{{ BaseQuery.printRequire() }}
{{ BaseQuery.printUse() }}
{% endif %}

/**
 * {{ Query }}
 */
class {{ Query }} extends{% if parentQuery %} {{ parentQuery}}{% else %} {{ BaseQuery }}{% endif %}
{

    /**
     * @return {{ Query }}
     */
    public function primaryKey($value, $comparison = \Query\Criterion::AUTO, $mutatorColumn = null, $mutatorValue = null){
        $this->whereAdd({{ Bean }}::{{ table.getPrimaryKey().getName().toUpperCase() }}, $value, $comparison, $mutatorColumn, $mutatorValue);
        return $this;
    }

    /**
     * initialization
     */
    protected function init(){
        $defaultColumn = array("{{ Bean }}.*");
        $this->from({{ Bean }}::TABLENAME, "{{ Bean }}");
{% set auxTable = table %}
{% for i in 1..5 %}
{% if auxTable.hasParent() %}
{% set auxTable = auxTable.getParent() %}
        $this->innerJoin{{ auxTable.getObject().toUpperCamelCase() }}('{{ auxTable.getObject().toUpperCamelCase() }}');
        $defaultColumn[] = "{{ auxTable.getObject().toUpperCamelCase() }}.*";
{% endif%}
{% endfor %}
        $this->setDefaultColumn($defaultColumn);
    }

    /**
     * (non-PHPdoc)
     * @see {{ classes.get('BaseQuery') }}::getCatalog()
     */
    protected function getCatalog(){
        return {{ Catalog }}::getInstance();
    }

{% for foreignKey in foreignKeys %}
    /**
     * @return {{ Query }}
     */
    public function innerJoin{{ foreignKey.getForeignTable().getObject() }}($alias = null)
    {
        if( null == $alias ){
            $alias = {{ foreignKey.getForeignTable().getObject().toUpperCamelCase() }}::TABLENAME;
        }

        $this->innerJoinOn({{ foreignKey.getForeignTable().getObject().toUpperCamelCase() }}::TABLENAME, $alias)
            ->equalFields('{{ Bean }}.{{ foreignKey.getLocal() }}', "$alias.{{ foreignKey.getForeign() }}");

        return $this;
    }

{% endfor %}
}