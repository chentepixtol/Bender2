{% include 'header.tpl' %}
{% set BaseForm = classes.get('BaseForm') %}{% if parent %}{% set parentPrimaryKey = parent.getPrimaryKey() %}{% endif %}
{% set t2 = '3' %}
{% set t2 = '3' %}
{% set t2 = '3' %}
{% set t2 = '3' %}
{% set t2 = '3' %}
{% set t2 = '3' %}
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
        $element = new Element\Text('{{ field.getName().toUnderscore() }}');
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