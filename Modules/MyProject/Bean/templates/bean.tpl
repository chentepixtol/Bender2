{% include 'header.tpl' %}

namespace {{ classes.get(Bean).getNamespace() }};

{% if parent %}
{{ classes.get(parent.getObject()).printRequire() }}
{% else %}
{{ classes.get('Bean').printRequire() }}

{{ classes.get('Bean').printUse() }}
{% endif %}

class {{ Bean }}{% if parent %} extends {{ parent.getObject() }}{% else %} implements {{ classes.get('Bean') }}{% endif %}
{

    const TABLENAME = '{{ table.getName() }}';

{% for field in fields %}
    const {{ field.getName().toUpperCase() }} = "{{ field.getName() }}";
{% endfor %}

{% for field in fields %}

    /**
     *
     */
    private ${{ field.getName().toCamelCase() }};
{% endfor %}

	/**
	 *
	 * @return int
	 */
	public function getIndex(){
		return $this->{{ table.getPrimaryKey().getter }}();
	}

{% for field in fields %}

    /**
     *
     */
	public function {{ field.getter }}(){
		return $this->{{ field.getName().toCamelCase() }};
	}

    /**
     * @param ${{ field.getName().toCamelCase()}}
     */
	public function {{ field.setter }}(${{ field.getName().toCamelCase()}}){
		$this->{{ field.getName().toCamelCase() }} = ${{ field.getName().toCamelCase()}};
	}
{% endfor %}


}