{% include 'header.tpl' %}

namespace {{ classes.get(Collection).getNamespace() }};

{{ classes.get('Collection').printRequire() }}

{{ classes.get('Collection').printUse() }}

/**
 *
 *
 * @author chente
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