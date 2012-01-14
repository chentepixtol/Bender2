{% include 'header.tpl' %}
{% set Bean = classes.get('Bean') %}
{% set Collectable = classes.get('Collectable') %}
{{ Bean.printNamespace() }}
{% if Bean.getNamespace() != Collectable.getNamespace() %}
{{ Collectable.printUse() }}
{% endif %}

{% include "header_class.tpl" with {'infoClass': Bean} %}
interface {{ Bean }} extends {{ Collectable }}
{

}
