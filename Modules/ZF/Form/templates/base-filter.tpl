{% include 'header.tpl' %}
{% set BaseFilter = classes.get('BaseFilter') %}
{{ BaseFilter.printNamespace() }}

/**
 *
 * {{ BaseFilter }}
 * @author chente
 *
 */
class {{ BaseFilter }}
{

    /**
     * 
     * @var array 
     */
    protected $elements = array();
    
    /**
     *
     */
    public function __construct(){}
    
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
    
     /**
     * @param string $fieldName
{%if isZF2 %}
     * @return \Zend\Filter\FilterChain
{% else %}
     * @return \Zend_Filter
{% endif %}
     */
    public function getFor($fieldName){
         if( !isset($this->elements[$fieldName]) ){
             throw new \InvalidArgumentException("No existe el validator ". $fieldName);
         }
         return $this->elements[$fieldName];
    }
    
    /**
     * @return array
     */
    public function toArray(){
        return $this->elements;
    }
    
}
