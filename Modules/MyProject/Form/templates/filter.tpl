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

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
{% for field in fields %}
        $this->init{{ field.getName().toUpperCamelCase }}Filter(); 
{% endfor %}
    }    
{% for field in fields %}

    /**
     *
     */
    protected function init{{ field.getName().toUpperCamelCase }}Filter()
    {
        $filter = new FilterChain();
        $this->elements['{{ field.getName() }}'] = $filter;
    }
{% endfor %}
}
