{% include 'header.tpl' %}

{{ classes.get(Factory).printNamespace() }}

{{ classes.get(Bean).printRequire() }}
{{ classes.get(Bean).printUse() }}

class {{ Factory }}
{

    /**
     *
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