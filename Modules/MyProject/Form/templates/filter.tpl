{% include 'header.tpl' %}
{% set BaseFilter = classes.get('BaseFilter') %}
{{ Filter.printNamespace() }}

/**
 *
 * {{ Filter }}
 * @author chente
 *
 */
class {{ Filter }} extends {% if parent %}{{ classes.get(parent.getObject()~'Filter') }}{% else %}{{ BaseFilter }}{% endif %}  
{

    
}
