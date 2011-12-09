{% include 'header.tpl' %}
{% set BaseForm = classes.get('BaseForm') %}
{{ BaseForm.printNamespace() }}

/**
 *
 * {{ BaseForm }}
 * @author chente
 *
 */
class {{ BaseForm }} extends \Zend_Form
{

    /**
     * init
     */
    public function init(){
    }
    
}
