{% include 'header.tpl' %}
{% set BaseBean = classes.get('Bean') %}
namespace {{ Bean.getNamespace() }};

{% if parent %}{{ classes.get(parent.getObject()).printRequire() }}{% else %}
{{ BaseBean.printRequire() }}
{% if BaseBean.getNamespace() != Bean.getNamespace() %}{{ BaseBean.printUse() }}{% endif %}
{% endif %}
class {{ Bean }}{% if parent %} extends {{ parent.getObject() }}{% else %} implements {{ BaseBean }}{% endif %}
{

    /**
     * TABLENAME
     */
    const TABLENAME = '{{ table.getName() }}';

    /**
     * Constants Fields
     */
{% for field in fields %}
    const {{ field.getName().toUpperCase() }} = '{{ field.getName() }}';
{% endfor %}
{% for field in fields %}

    /**
     * @var {{ field.cast('php') }}
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
     * @return {{ field.cast('php') }}
     */
    public function {{ field.getter }}(){
        return $this->{{ field.getName().toCamelCase() }};
    }

    /**
     * @param {{ field.cast('php') }} ${{ field.getName().toCamelCase()}}
     * @return {{ Bean }}
     */
    public function {{ field.setter }}(${{ field.getName().toCamelCase()}}){
        $this->{{ field.getName().toCamelCase() }} = ${{ field.getName().toCamelCase()}};
        return $this;
    }
{% endfor %}

    /**
     * Convert to array
     * @return array
     */
    public function toArray()
    {
        $array = array(
{% for field in fields %}
            '{{ field.getName()}}' => $this->{{ field.getter }}(),
{% endfor %}
        );
{%if parent %}
        return array_merge(parent::toArray(), $array);
{% else %}
        return $array;
{% endif %}
    }

}