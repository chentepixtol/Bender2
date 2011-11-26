{% include 'header.tpl' %}
{% set Bean = classes.get('Bean') %}
{% set Catalog = classes.get('Catalog') %}
{% set Collection = classes.get('Collection') %}

{{ Catalog.printNamespace() }}

/**
 *
 * {{ Catalog }}
 * @author chente
 *
 */
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
     *
     * @return {{ Collection }}
     */
    public function getByQuery($query);

    /**
     *
     * @return {{ Bean }}
     */
    public function getOneByQuery($query);

    /**
     *
     * @return array
     */
    public function fetchAll($query);

    /**
     *
     * @return array
     */
    public function fetchCol($query);

    /**
     *
     * @return mixed
     */
    public function fetchOne($query);

    /**
     *
     * @return mixed
     */
    public function fetchPairs($query);

}