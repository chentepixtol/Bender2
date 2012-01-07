{% include 'header.tpl' %}
{% set BaseQuery = classes.get('BaseQuery') %}
{{ Query.printNamespace() }}

use Query\Query;
{{ Catalog.printUse() }}
{{ Bean.printUse() }}

{% if parent %}
{% set parentQuery = classes.get(parent.getObject()~'Query') %}
{% else %}
{{ BaseQuery.printUse() }}
{% endif %}

/**
 * {{ Query }}
 */
class {{ Query }} extends{% if parentQuery %} {{ parentQuery}}{% else %} {{ BaseQuery }}{% endif %}
{

    /**
     * (non-PHPdoc)
     * @see {{ classes.get('BaseQuery') }}::getCatalog()
     */
    protected function getCatalog(){
        return {{ Catalog }}::getInstance();
    }

    /**
     * initialization
     */
    protected function init()
    {
        $this->from({{ Bean }}::TABLENAME, "{{ Bean }}");
{% set auxTable = table %}
{% for i in 1..5 %}
{% if auxTable.hasParent() %}
{% set auxTable = auxTable.getParent() %}
        $this->innerJoin{{ auxTable.getObject().toUpperCamelCase() }}();
{% endif%}
{% endfor %}

        $defaultColumn = array("{{ Bean }}.*");
{% set auxTable = table %}
{% for i in 1..5 %}
{% if auxTable.hasParent() %}
{% set auxTable = auxTable.getParent() %}
        $defaultColumn[] = "{{ auxTable.getObject().toUpperCamelCase() }}.*";
{% endif%}
{% endfor %}
        $this->setDefaultColumn($defaultColumn);
    }
    
    /**
     * @return {{ Query }}
     */
    public function primaryKey($value, $comparison = \Query\Criterion::AUTO, $mutatorColumn = null, $mutatorValue = null){
        $this->whereAdd({{ Bean }}::{{ table.getPrimaryKey().getName().toUpperCase() }}, $value, $comparison, $mutatorColumn, $mutatorValue);
        return $this;
    }
    
    /**
     * build fromArray
     * @param array $fields
     * @param string $prefix
     * @return {{ Query }}
     */
    public function filter($fields, $prefix = '{{ Bean }}'){
        $this->build($this, $fields, $prefix);
        return $this;
    }
    
    /**
     * build fromArray
     * @param Query $query
     * @param array $fields
     * @param string $prefix
     */
    public static function build(Query $query, $fields, $prefix = '{{ Bean }}')
    {
{% if parent %}
        parent::build($query, $fields);    
{% endif %}

        $criteria = $query->where();
        $criteria->prefix($prefix);
        
{% for field in fields %}
        if( isset($fields['{{ field }}']) && !empty($fields['{{ field }}']) ){
            $criteria->add({{ Bean }}::{{ field.getName().toUpperCase() }}, $fields['{{ field }}']);
        }
{% endfor %}

        $criteria->endPrefix();
    }

{% for foreignKey in foreignKeys %}
{% set classForeign = classes.get(foreignKey.getForeignTable().getObject().toUpperCamelCase()) %}
    /**
     * @param string $alias
     * @param string aliasForeignTable
     * @return {{ Query }}
     */
    public function innerJoin{{ classForeign }}($alias = '{{ Bean }}', $aliasForeignTable = '{{ classForeign }}')
    {
        $this->innerJoinOn(\{{ classForeign.getFullName() }}::TABLENAME, $aliasForeignTable)
            ->equalFields(array($alias, '{{ foreignKey.getLocal() }}'), array($aliasForeignTable, '{{ foreignKey.getForeign() }}'));

        return $this;
    }

{% endfor %}

}