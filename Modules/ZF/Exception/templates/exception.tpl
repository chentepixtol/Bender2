{% include 'header.tpl' %}
{{ Exception.printNamespace() }}

{% include "header_class.tpl" with {'infoClass': Exception} %}
class {{ Exception }} extends {% if parent %}{{ parent.getObject()~'Exception' }}{% else %}\Exception{% endif %}
{

}