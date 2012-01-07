{% include 'header.tpl' %}
{% set BaseValidator = classes.get('BaseValidator') %}
{{ Validator.printNamespace() }}


{%if isZF2 %}
use Zend\Validator\ValidatorChain as ZendValidator;
{% else %}
use Zend_Validate as ZendValidator;
{% endif %}

/**
 *
 * {{ Validator }}
 * @author chente
 *
 */
class {{ Validator }} extends {% if parent %}{{ classes.get(parent.getObject()~'Validator') }}{% else %}{{ BaseValidator }}{% endif %}  
{

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
{% for field in fields %}
        $this->init{{ field.getName().toUpperCamelCase }}Validator(); 
{% endfor %}
    }    
{% for field in fields %}

    /**
     *
     */
    protected function init{{ field.getName().toUpperCamelCase }}Validator()
    {
        $validator = new ZendValidator();
        $this->elements['{{ field.getName() }}'] = $validator;
    }
{% endfor %}

 }
