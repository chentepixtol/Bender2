{% include 'header.tpl' %}
{% set BaseForm = classes.get('BaseForm') %}
{{ Form.printNamespace() }}

{{ Validator.printUse() }}
{{ Filter.printUse() }}

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
     * @var Zend_Form_Element
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
     * @return Zend_Form_Element
     */
    public function {{ field.getter }}Element()
    {
        if( null == $this->{{ field.getName().toCamelCase() }} ){
            $this->{{ field.getName().toCamelCase() }} = new \Zend_Form_Element_Text('{{ field.getName().toUnderscore() }}');
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
