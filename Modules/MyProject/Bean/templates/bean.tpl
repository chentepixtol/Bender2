{% include 'header.tpl' %}

{% if parent %}
require_once '{{ routes.getRoute( parent.getObject().toUpperCamelCase() ) }}';
{% endif %}

class {{ Bean }} {% if parent %}extends {{ parent.getObject().toUpperCamelCase() }} {% endif %}
{

    const TABLENAME = '{{ table.getName() }}';


{% for field in fields %}
    const {{ field.getName().toUpperCase() }} = "{{ field.getName() }}";
{% endfor %}

}