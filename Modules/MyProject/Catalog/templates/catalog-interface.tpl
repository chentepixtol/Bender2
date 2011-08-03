{% include 'header.tpl' %}

{% set Bean = classes.get('Bean') %}
{% set Catalog = classes.get('Catalog') %}
{% set Collection = classes.get('Collection') %}


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
     * Elimina de  la base de datos
     * @param {{ Bean }} El bean que se va a eliminar
     */
    public function delete($object);

    /**
     * Elimina de la base de datos por medio de llave primaria
     * @param int $idObject El id del bean que se eliminara
     */
    public function deleteById($idObject);

    /**
     * Obtiene un {{ Bean }} de la base de datos
     * @param int $idObject
     * @return mixed EL BEAN
     */
    public function getById($idObject);

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

}