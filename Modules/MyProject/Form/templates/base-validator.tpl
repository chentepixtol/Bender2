{% include 'header.tpl' %}
{% set BaseValidator = classes.get('BaseValidator') %}
{{ BaseValidator.printNamespace() }}

/**
 *
 * {{ BaseValidator }}
 * @author chente
 *
 */
class {{ BaseValidator }} extends \Zend_Validate
{

    
}
