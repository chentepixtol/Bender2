{% include 'header.tpl' %}
{{ classes.get('Factory').printNamespace() }}

{% include "header_class.tpl" with {'infoClass': classes.get('Factory')} %}
interface {{ classes.get('Factory') }}
{

    /**
     *
     * @static
     * @param array $fields
     * @return {{ classes.get('Bean') }}
     */
    public static function createFromArray($fields);
    
    /**
     *
     * @static
     * @param {{ classes.get('Bean') }} $bean
     * @param array $fields
     */
    public static function populate($bean, $fields);

}
