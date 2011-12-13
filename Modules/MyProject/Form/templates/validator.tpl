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
        $validator = new ValidatorChain();
        $this->elements['{{ field.getName() }}'] = $validator;
    }
{% endfor %}

 }
