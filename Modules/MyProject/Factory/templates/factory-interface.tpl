{% include 'header.tpl' %}

{{ classes.get('Factory').printNamespace() }}

/**
 *
 * {{ classes.get('Factory') }}
 * @author chente
 *
 */
interface {{ classes.get('Factory') }}
{

    /**
     *
     * @static
     * @param array $fields
     * @return {{ classes.get('Bean') }}
     */
	public static function createFromArray($fields);

}
