{% include 'header.tpl' %}
{% set BaseFilter = classes.get('BaseFilter') %}
{{ BaseFilter.printNamespace() }}

/**
 *
 * {{ BaseFilter }}
 * @author chente
 *
 */
class {{ BaseFilter }} extends \Zend_Filter
{
   
}
