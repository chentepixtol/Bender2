{% include 'header.tpl' %}
{% set BaseForm = classes.get('BaseForm') %}{% if parent %}{% set parentPrimaryKey = parent.getPrimaryKey() %}{% endif %}
{{ Form.printNamespace() }}

{{ Validator.printUse() }}
{{ Filter.printUse() }}

{% if isZF2 %}
use Zend\Form\Element;
{% set ElementText = 'Element\\Text' %}
{% else %}
use Zend_Form_Element_Text as ElementText;
{% set ElementText = 'ElementText' %}
{% endif %}

/**
 *
 * {{ Form }}
 * @author chente
 *
 */
class {{ Form }} extends {% if parent %}{{ classes.get(parent.getObject()~'Form') }}{% else %}{{ BaseForm }}{% endif %}  
{

    /**
     * init
     */
    public function init()
    {
        parent::init();
        $this->validator = new {{ Validator }}();
        $this->filter = new {{ Filter }}();
                
{% for field in fields.nonPrimaryKeys() %}
{% if field.getName().toString() != parentPrimaryKey.getName().toString() %}
        $this->init{{ field.getName().toUpperCamelCase() }}Element();
{% endif %}
{% endfor %}
    }
        
{% for field in fields.nonPrimaryKeys() %}{% if field.getName().toString() != parentPrimaryKey.getName().toString() %}

    /**
     *
     */
    protected function init{{ field.getName().toUpperCamelCase() }}Element()
    {
        $element = new {{ ElementText }}('{{ field.getName().toUnderscore() }}');
        $element->setLabel('{{ field.getName().toUpperCamelCase() }}');
        $element->addValidator($this->validator->getFor('{{ field.getName() }}'));
        $element->addFilter($this->filter->getFor('{{ field }}'));
{% if field.isRequired %}
        $element->setRequired(true);
{% endif %}            
        $this->addElement($element);
        $this->elements['{{ field.getName().toUnderscore() }}'] = $element;
    }
{% endif %}{% endfor %}

}