{% include 'header.tpl' %}
{% set BaseValidator = classes.get('BaseValidator') %}
{{ Validator.printNamespace() }}

/**
 *
 * {{ Validator }}
 * @author chente
 *
 */
class {{ Validator }} extends {% if parent %}{{ classes.get(parent.getObject()~'Validator') }}{% else %}{{ BaseValidator }}{% endif %}  
{

    
}
