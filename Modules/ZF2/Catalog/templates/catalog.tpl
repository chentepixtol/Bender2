{% include 'header.tpl' %}
{% set AbstractCatalog = classes.get('AbstractCatalog') %}
{% set primaryKey = table.getPrimaryKey().getName().toCamelCase() %}
{% set BaseBean = classes.get('Bean') %}
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
{{ BaseBean.printUse() }}
{{ Query.printUse() }}
use Query\Query;

/**
 *
 * {{ Catalog }}
 * @author chente
 * @method PersonCatalog getInstance
 * @method {{ Bean }} getOneByQuery
 * @method {{ Collection }} getByQuery
 */
class {{ Catalog }} extends {% if parent %}{{ classes.get(parent.getObject() ~ 'Catalog') }}{% else %}{{ AbstractCatalog }} {% endif %}
{

    /**
     * Metodo para agregar un {{ Bean }} a la base de datos
     * @param {{ Bean }} ${{ bean }} Objeto {{ Bean }}
     */
    public function create(${{ bean }})
    {
        $this->validateBean(${{ bean }});
        try
        {
{% if parent %}
            if( !${{ bean }}->{{ parent.getPrimaryKey().getter() }}() ){
              parent::create(${{ bean }});
            }

{% endif %}
            $data = ${{ bean}}->toArrayFor(
                array({% for field in fields.nonPrimaryKeys %}'{{ field.getName() }}',{% endfor %})
            );
            $data = array_filter($data, array($this, 'isNotNull'));
            $this->getDb()->insert({{ Bean }}::TABLENAME, $data);
{% if table.hasPrimaryKey() %}
            ${{ bean }}->{{ table.getPrimaryKey().setter() }}($this->getDb()->lastInsertId());
{% endif %}
        }
        catch(\Exception $e)
        {
            $this->throwException("The {{ Bean }} can't be saved \n", $e);
        }
    }

    /**
     * Metodo para actualizar un {{ Bean }} en la base de datos
     * @param {{ Bean }} ${{ bean }} Objeto {{ Bean }}
     */
    public function update(${{ bean }})
    {
        $this->validateBean(${{ bean }});
        try
        {
            $data = ${{ bean}}->toArrayFor(
                array({% for field in fields.nonPrimaryKeys %}'{{ field.getName() }}',{% endfor %})
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
            $this->throwException("The {{ Bean }} can't be saved \n", $e);
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
            $this->throwException("The {{ Bean }} can't be deleted\n", $e);
        }
    }

    /**
     *
     * makeCollection
     * @return {{ Collection }}
     */
    protected function makeCollection(){
        return new {{ Collection }}();
    }

    /**
     *
     * makeBean
     * @param array $resultset
     * @return {{ Bean }}
     */
    protected function makeBean($resultset){
    	return {{ Factory }}::createFromArray($resultset);
    }

    /**
     *
     * Validate Query
     * @param {{ Query }} $query
     * @throws RoundException
     */
    protected function validateQuery(Query $query)
    {
    	if( !($query instanceof {{ Query }}) ){
    	    $this->throwException("No es un Query valido");
    	}
    }
    
    /**
     *
     * Validate {{ Bean }}
     * @param {{ Bean }} ${{ bean }}
     * @throws Exception
     */
    protected function validateBean({{ BaseBean }} ${{ bean }} = null){
        if( !(${{ bean }} instanceof {{ Bean }}) ){
            $this->throwException("passed parameter isn't a {{ Bean }} instance");
        }
    }

    /**
     *
     * throwException
     * @throws Exception
     */
    protected function throwException($message, \Exception $exception = null){
    	if( null != $exception){
    	    throw new {{ Exception }}("$message ". $exception->getMessage(), 500, $exception);
    	}else{
    	    throw new {{ Exception }}($message);
    	}
    }

 }