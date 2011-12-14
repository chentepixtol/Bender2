{% include 'header.tpl' %}
{{ Factory.printNamespace() }}

{{ classes.get(Bean).printRequire() }}
{{ classes.get('Factory').printRequire() }}
{{ classes.get(Bean).printUse() }}
{% if classes.get('Factory').getNamespace() != Factory.getNamespace() %}{{ classes.get('Factory').printUse() }}{% endif %}

class {{ Factory }} implements {{ classes.get('Factory') }}
{

    /**
     *
     * @static
     * @param array $fields
     * @return {{ Bean }}
     */
    public static function createFromArray($fields)
    {
        ${{ bean }} = new {{ Bean }}();

{% for field in fields %}
        ${{ bean }}->{{ field.setter }}($fields['{{ field }}']);
{% endfor %}

        return ${{ bean }};
    }

}