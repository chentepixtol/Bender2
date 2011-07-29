{% include 'header.tpl' %}

{{ classes.get('Bean').printNamespace() }}

{{ classes.get('Collectable').printUse() }}

{{ classes.get('Collectable').printRequire() }}

/**
 *
 * {{ classes.get('Bean') }}
 * @author chente
 *
 */
interface {{ classes.get('Bean') }} extends {{ classes.get('Collectable') }}
{

}
