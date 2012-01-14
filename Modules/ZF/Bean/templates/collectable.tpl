{% include 'header.tpl' %}
{% set Collectable = classes.get('Collectable') %}
{{ Collectable.printNamespace() }}

{% include "header_class.tpl" with {'infoClass': Collectable} %}
interface {{ Collectable }}
{

    /**
     *
     * @return int
     */
    public function getIndex();

    /**
     * Convert to array
     * @return array
     */
    public function toArray();
    
    /**
     * Convert to array
     * @return array
     */
    public function toArrayFor($fields);
    

}
