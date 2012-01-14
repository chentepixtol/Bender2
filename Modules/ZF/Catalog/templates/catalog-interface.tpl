{% include 'header.tpl' %}
{% set Bean = classes.get('Bean') %}
{% set Catalog = classes.get('Catalog') %}
{% set Collection = classes.get('Collection') %}
{% set Storage = classes.get('Storage') %}
{% set storage = Storage.getName().toCamelCase() %}
{{ Catalog.printNamespace() }}

{{ Storage.printUse() }}
use Query\Query;

{% include "header_class.tpl" with {'infoClass': Catalog} %}
interface {{ Catalog }}
{

    /**
     * Singleton
     * @return {{ Catalog }}
     */
    public static function getInstance();

    /**
     * beginTransaction
     */
    public function beginTransaction();

    /**
     * commit
     */
    public function commit();

    /**
     * rollBack
     */
    public function rollBack();

    /**
     * Guarda en la base de datos
     * @param {{ Bean }}  Un bean para guardar
     */
    public function create($object);

    /**
     * Actualiza el objeto en la base de datos
     * @param {{ Bean }} Un bean para actualizar
     */
    public function update($object);

    /**
     * Elimina de la base de datos por medio de llave primaria
     * @param int $idObject El id del bean que se eliminara
     */
    public function deleteById($idObject);

    /**
     * getByQuery
     * @param Query $query
     * @param {{  Storage }} ${{  storage }}
     * @return {{ Collection }}
     */
    public function getByQuery(Query $query, {{  Storage }} ${{  storage }} = null);

    /**
     * @param Query $query
     * @return {{ Bean }}
     */
    public function getOneByQuery(Query $query, {{  Storage }} ${{  storage }} = null, $throwIfNotExists = false);

    /**
     *
     * @return array
     */
    public function fetchAll(Query $query, {{  Storage }} ${{  storage }} = null);

    /**
     *
     * @return array
     */
    public function fetchCol(Query $query, {{  Storage }} ${{  storage }} = null);

    /**
     *
     * @return mixed
     */
    public function fetchOne(Query $query, {{  Storage }} ${{  storage }} = null);

    /**
     *
     * @return mixed
     */
    public function fetchPairs(Query $query, {{  Storage }} ${{  storage }} = null);

}