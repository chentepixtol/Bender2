{% include 'header.tpl' %}
{% set Bean = classes.get('Bean') %}
{% set Collectable = classes.get('Collectable') %}
{{ Bean.printNamespace() }}
{% if Bean.getNamespace() != Collectable.getNamespace() %}
{{ Collectable.printUse() }}
{% endif %}
{{ Collectable.printRequire() }}

/**
 *
 * {{ Bean }}
 * @author chente
 *
 */
interface {{ Bean }} extends {{ Collectable }}
{

}
