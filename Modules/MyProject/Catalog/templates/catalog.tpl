{% include 'header.tpl' %}
{% set AbstractCatalog = classes.get('AbstractCatalog') %}

{{ Catalog.printNamespace() }}

{{ AbstractCatalog.printRequire() }}
{{ classes.get(Bean).printRequire() }}
{{ classes.get(Factory).printRequire() }}
{{ classes.get(Collection).printRequire() }}
{{ classes.get(Exception).printRequire() }}

{{ AbstractCatalog.printUse() }}
{{ classes.get(Bean).printUse() }}
{{ classes.get(Factory).printUse() }}
{{ classes.get(Collection).printUse() }}
{{ classes.get(Exception).printUse() }}

/**
 *
 * {{ Catalog }}
 * @author chente
 * @method PersonCatalog getInstance
 */
class {{ Catalog }} extends {% if parent %}{{ classes.get(parent.getObject() ~ 'Catalog') }}{% else %}{{ AbstractCatalog }} {% endif %}
{

    /**
     * Metodo para agregar un {{ Bean }} a la base de datos
     * @param {{ Bean }} ${{ bean }} Objeto {{ Bean }}
     */
    public function create(${{ bean }})
    {
        if( !(${{ bean }} instanceof {{ Bean }}) ){
            throw new {{ Exception }}("passed parameter isn't a {{ Bean }} instance");
        }

        try
        {
{% if parent %}
            if( !${{ bean }}->{{ parent.getPrimaryKey().getter() }}() ){
              parent::create(${{ bean }});
            }

{% endif %}
            $data = array(
{% for field in fields %}
{% if field.isPrimaryKey() == false %}
                '{{ field.getName() }}' => ${{ bean }}->{{ field.getter() }}(),
{% endif %}
{% endfor %}
            );
            $data = array_filter($data, array($this, 'notNull'));
            $this->getDb()->insert({{ Bean }}::TABLENAME, $data);
{% if table.hasPrimaryKey() %}
            ${{ bean }}->{{ table.getPrimaryKey().setter() }}($this->getDb()->lastInsertId());
{% endif %}
        }
        catch(Exception $e)
        {
            throw new {{ Exception }}("The {{ Bean }} can't be saved \n" . $e->getMessage(), 500, $e);
        }
    }

    /**
     * Actualiza el objeto en la base de datos
     * @param Bean Un bean para actualizar
     */
    public function update($object);

    /**
     * Elimina de la base de datos por medio de llave primaria
     * @param int $idObject El id del bean que se eliminara
     */
    public function deleteById($idObject);

    /**
     *
     * @return Collection
     */
    public function getByQuery($query);

    /**
     *
     * @return Bean
     */
    public function getOneByQuery($query);

 }