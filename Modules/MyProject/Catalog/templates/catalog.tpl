{% include 'header.tpl' %}
{% set AbstractCatalog = classes.get('AbstractCatalog') %}
{% set primaryKey = table.getPrimaryKey().getName().toCamelCase() %}

{{ Catalog.printNamespace() }}

{{ AbstractCatalog.printRequire() }}
{{ Bean.printRequire() }}
{{ Factory.printRequire() }}
{{ Collection.printRequire() }}
{{ Exception.printRequire() }}

{{ AbstractCatalog.printUse() }}
{{ Bean.printUse() }}
{{ Factory.printUse() }}
{{ Collection.printUse() }}
{{ Exception.printUse() }}

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
            $data = array_filter($data, array($this, 'isNotNull'));
            $this->getDb()->insert({{ Bean }}::TABLENAME, $data);
{% if table.hasPrimaryKey() %}
            ${{ bean }}->{{ table.getPrimaryKey().setter() }}($this->getDb()->lastInsertId());
{% endif %}
        }
        catch(\Exception $e)
        {
            throw new {{ Exception }}("The {{ Bean }} can't be saved \n" . $e->getMessage(), 500, $e);
        }
    }

    /**
     * Metodo para actualizar un {{ Bean }} en la base de datos
     * @param {{ Bean }} ${{ bean }} Objeto {{ Bean }}
     */
    public function update(${{ bean }})
    {
        if( !(${{ bean }} instanceof {{ Bean }}) ){
            throw new {{ Exception }}("passed parameter isn't a {{ Bean }} instance");
        }
        try
        {
            $data = array(
{% for field in fields %}
{% if field.isPrimaryKey() == false %}
                '{{ field.getName() }}' => ${{ bean }}->{{ field.getter() }}(),
{% endif %}
{% endfor %}
            );
            $data = array_filter($data, array($this, 'isNotNull'));
            $this->getDb()->update({{ Bean }}::TABLENAME, $data, "{{ table.getPrimaryKey() }} = '{${{ bean }}->{{ table.getPrimaryKey().getter() }}()}'");
{% if table.hasPrimaryKey() %}
            ${{ bean }}->{{ table.getPrimaryKey().setter() }}($this->getDb()->lastInsertId());
{% endif %}
{% if parent %}
            parent::update(${{ bean }});
{% endif %}
        }
        catch(\Exception $e)
        {
            throw new {{ Exception }}("The {{ Bean }} can't be saved \n" . $e->getMessage(), 500, $e);
        }
    }

    /**
     * Metodo para eliminar un {{ Bean }} a partir de su Id
     * @param int ${{ primaryKey }}
     */
    public function deleteById(${{ primaryKey }})
    {
        try
        {
            $where = array($this->db->quoteInto('{{ table.getPrimaryKey() }} = ?', ${{ primaryKey }}));
            $this->db->delete({{ Bean }}::TABLENAME, $where);
        }
        catch(\Exception $e)
        {
            throw new {{ Exception }}("The {{ Bean }} can't be deleted\n" . $e->getMessage());
        }
    }

    /**
     *
     * @param {{ Query }} ${{ query }}
     * @return {{ Collection }}
     */
    public function getByQuery(${{ query }})
    {
    	if( !(${{query}} instaceof {{ Query }}) ){
    		throw new {{ Exception }}("No es un Query valido");
    	}

    	$this->db->setFetchMode(Zend_Db::FETCH_ASSOC);
        try
        {
            ${{ collection }} = new {{ Collection }}();
            foreach ($this->db->fetchAll(${{ query }}->createSql()) as $row){
                ${{ collection }}->append({{ Factory }}::createFromArray($row)));
            }
        }
        catch(\Exception $e)
        {
            throw new {{ Exception }}("Cant obtain {{ Collection }}\n" . $e->getMessage());
        }
        return ${{ collection }};

    }

    /**
     *
     * @param ${{ query }} {{ Query }}
     * @return {{ Bean }}
     */
    public function getOneByQuery(${{ query }})
    {
    	if( !(${{query}} instaceof {{ Query }}) ){
    		throw new {{ Exception }}("No es un Query valido");
    	}

    	return $this->getByQuery(${{query}})->getOne();
    }

 }