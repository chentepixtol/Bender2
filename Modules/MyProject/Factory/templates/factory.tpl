{% include 'header.tpl' %}

{{ classes.get(Factory).printNamespace() }}

{{ classes.get(Bean).printRequire() }}
{{ classes.get('Factory').printRequire() }}
{{ classes.get(Bean).printUse() }}
{{ classes.get('Factory').printUse() }}

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