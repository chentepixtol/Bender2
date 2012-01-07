{% include 'header.tpl' %}

namespace {{ classes.get(Exception).getNamespace() }};

{% if parent %}
{{ classes.get(parent.getObject()~'Exception').printRequire() }}
{% endif %}

class {{ Exception }} extends {% if parent %}{{ parent.getObject()~'Exception' }}{% else %}\Exception{% endif %}
{

}