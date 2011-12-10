{% include 'header.tpl' %}
{% set BaseForm = classes.get('BaseForm') %}
{{ Form.printNamespace() }}

{{ Validator.printUse() }}
{{ Filter.printUse() }}

use Zend\Form\Element;

/**
 *
 * {{ Form }}
 * @author chente
 *
 */
class {{ Form }} extends {% if parent %}{{ classes.get(parent.getObject()~'Form') }}{% else %}{{ BaseForm }}{% endif %}  
{

    /**
     * @var {{ Validator }} $validator
     */
    protected $validator;
    
    /**
     * @var {{ Filter }} $filter
     */
    protected $filter;
{% for field in fields %}

    /**
     *
     * @var Zend\Form\Element
     */
    private ${{ field.getName().toCamelCase() }};
{% endfor %}


    /**
     * init
     */
    public function init()
    {
        parent::init();
        $this->validator = new {{ Validator }}();
        $this->filter = new {{ Filter }}();
        
{% for field in fields %}
        $this->addElement($this->{{ field.getter }}Element());
{% endfor %}
    }
        
{% for field in fields %}

    /**
     *
     * @return Zend\Form\Element
     */
    public function {{ field.getter }}Element()
    {
        if( null == $this->{{ field.getName().toCamelCase() }} ){
{% if field.isPrimaryKey %}
            $this->{{ field.getName().toCamelCase() }} = new Element\Hidden('{{ field.getName().toUnderscore() }}');
{% elseif field.isBoolean %}
            $this->{{ field.getName().toCamelCase() }} = new Element\Checkbox('{{ field.getName().toUnderscore() }}');
{% else %}
            $this->{{ field.getName().toCamelCase() }} = new Element\Text('{{ field.getName().toUnderscore() }}');
{% endif %}
            $this->{{ field.getName().toCamelCase() }}->setLabel('{{ field.getName().toUpperCamelCase() }}');
            $this->{{ field.getName().toCamelCase() }}->addValidator(
                $this->validator->{{ field.getter }}Validator()
            );
            $this->{{ field.getName().toCamelCase() }}->addFilter(
                $this->filter->{{ field.getter }}Filter()
            );
        }
    
        return $this->{{ field.getName().toCamelCase() }};
    }
{% endfor %}

}
