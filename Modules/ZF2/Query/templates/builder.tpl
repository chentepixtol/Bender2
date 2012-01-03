{% include 'header.tpl' %}
{% if parent %}
{% set parentQueryBuilder = classes.get(parent.getObject()~'QueryBuilder') %}
{% endif %}
{{ QueryBuilder.printNamespace() }}

use Query\Query;
use Query\Criterion;
{{ Bean.printUse() }}

/**
 * {{ QueryBuilder }}
 */
class {{ QueryBuilder }}{% if parent %} extends {{ parentQueryBuilder }}{% endif %}
{

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
        if( isset($fields['{{ field }}']) ){
            $criteria->add({{ Bean }}::{{ field.getName().toUpperCase() }}, $fields['{{ field }}']);
        }
{% endfor %}

        $criteria->endPrefix();
    }
    
}