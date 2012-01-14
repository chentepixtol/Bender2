{% include 'header.tpl' %}
{{ Collection.printNamespace() }}

{% if Collection.getNamespace != classes.get('Collection').getNamespace() %}{{ classes.get('Collection').printUse() }}{% endif %}

/**
 *
 * {{ Collection }}
 *
 * @author {{ meta.get('author') }}
 * @method {{ Bean }} current
 * @method {{ Bean }} read
 * @method {{ Bean }} getOne
 * @method {{ Bean }} getByPK
 * @method {{ Collection }} intersect
 * @method {{ Collection }} filter
 */
class {{ Collection }} extends {{ classes.get('Collection') }}
{

}