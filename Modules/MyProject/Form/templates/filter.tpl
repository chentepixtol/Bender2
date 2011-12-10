{% include 'header.tpl' %}
{% set BaseFilter = classes.get('BaseFilter') %}
{{ Filter.printNamespace() }}

use Zend\Filter\FilterChain;

/**
 *
 * {{ Filter }}
 * @author chente
 *
 */
class {{ Filter }} extends {% if parent %}{{ classes.get(parent.getObject()~'Filter') }}{% else %}{{ BaseFilter }}{% endif %}  
{
{% for field in fields %}

    /**
     *
     * @var Zend\Filter\FilterChain
     */
    private ${{ field.getName().toCamelCase() }};
{% endfor %}       
{% for field in fields %}

    /**
     *
     * @return Zend\Filter\FilterChain
     */
    public function {{ field.getter }}Filter()
    {
        if( null == $this->{{ field.getName().toCamelCase() }} ){
            $this->{{ field.getName().toCamelCase() }} = new FilterChain();
        }
    
        return $this->{{ field.getName().toCamelCase() }};
    }
{% endfor %}

    /**
     * Convert to array
     * @return array
     */
    public function toArray()
    {
        $array = array(
{% for field in fields %}
            '{{ field.getName()}}' => $this->{{ field.getter }}Filter(),
{% endfor %}
        );
{%if parent %}
        return array_merge(parent::toArray(), $array);
{% else %}
        return $array;
{% endif %}
    }
    
}
