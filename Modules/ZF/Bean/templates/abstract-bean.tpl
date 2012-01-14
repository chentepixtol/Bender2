{% include 'header.tpl' %}
{% set BaseBean = classes.get('Bean') %}
{% set AbstractBean = classes.get('AbstractBean') %}
{{ AbstractBean.printNamespace() }}
{% if BaseBean.getNamespace() != AbstractBean.getNamespace() %}
{{ BaseBean.printUse() }}
{% endif %}

{% include "header_class.tpl" with {'infoClass': AbstractBean} %}
abstract class {{ AbstractBean }} implements {{ BaseBean }} 
{

    /**
     * Convert to array
     * @return array
     */
    public function toArrayFor($fields){
        $array = array();
        $all = $this->toArray();
        foreach($fields as $field){
            if( array_key_exists($field, $all) ){
                $array[$field] = $all[$field];    
            }
        }
        return $array;
    }
    
}
