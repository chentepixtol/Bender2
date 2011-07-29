{% include 'header.tpl' %}

namespace {{ classes.get('Bean').getNamespace() }};

{% if parent %}
require_once '{{ classes.get( parent.getObject().toUpperCamelCase() ).getRoute() }}';
{% else %}
use {{ classes.get('Bean').getNamespacedName() }};
{% endif %}

class {{ Bean }}{% if parent %} extends {{ parent.getObject().toUpperCamelCase() }}{% else %} implements Bean{% endif %}
{

    const TABLENAME = '{{ table.getName() }}';

{% for field in fields %}
    const {{ field.getName().toUpperCase() }} = "{{ field.getName() }}";
{% endfor %}

}