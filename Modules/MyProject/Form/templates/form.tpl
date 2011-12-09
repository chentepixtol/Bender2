{% include 'header.tpl' %}
{% set BaseForm = classes.get('BaseForm') %}
{{ Form.printNamespace() }}

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
    public function init(){
        parent::init();
    }
    
}
