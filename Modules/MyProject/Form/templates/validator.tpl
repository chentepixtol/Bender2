{% include 'header.tpl' %}
{% set BaseValidator = classes.get('BaseValidator') %}
{{ Validator.printNamespace() }}

use Zend\Validator\ValidatorChain;

/**
 *
 * {{ Validator }}
 * @author chente
 *
 */
class {{ Validator }} extends {% if parent %}{{ classes.get(parent.getObject()~'Validator') }}{% else %}{{ BaseValidator }}{% endif %}  
{
{% for field in fields %}

    /**
     *
     * @var Zend\Validator\ValidatorChain
     */
    private ${{ field.getName().toCamelCase() }};
{% endfor %}       
{% for field in fields %}

    /**
     *
     * @return Zend\Validator\ValidatorChain
     */
    public function {{ field.getter }}Validator()
    {
        if( null == $this->{{ field.getName().toCamelCase() }} ){
            $this->{{ field.getName().toCamelCase() }} = new ValidatorChain();
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
            '{{ field.getName()}}' => $this->{{ field.getter }}Validator(),
{% endfor %}
        );
{%if parent %}
        return array_merge(parent::toArray(), $array);
{% else %}
        return $array;
{% endif %}
    }
}
