{% include 'header.tpl' %}
{% set BaseFilter = classes.get('BaseFilter') %}
{{ BaseFilter.printNamespace() }}

/**
 *
 * {{ BaseFilter }}
 * @author chente
 *
 */
abstract class {{ BaseFilter }}
{

   /**
     * get Filters
     * @return array
     */
    abstract public function toArray();
    
    /**
     * isValid
     * @param array $array
     * @return array
     */
    public function filter(array $array)
    {   
        $newArray = array();
        foreach( $this->toArray() as $field  => $filter ){
            $newArray[$field] = $filter->filter($array[$field]);
        }
        return $newArray;
    }
    
}
