{% include 'header.tpl' %}
{% set BaseValidator = classes.get('BaseValidator') %}
{{ BaseValidator.printNamespace() }}

/**
 *
 * {{ BaseValidator }}
 * @author chente
 *
 */
class {{ BaseValidator }}
{

    /**
     * 
     * @var array 
     */
    private $messages = array();
    
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
     * @return boolean
     */
    public function isValid(array $array)
    {
        $isValid = true;
        $this->messages = array();
        
        foreach( $this->toArray() as $field  => $validate ){
            if( !$validate->isValid($array[$field]) ){
                $isValid = false;
                $this->addMessage($field, $validate->getMessages());
            }
        }
        return $isValid;
    }
    
    /**
     * @param string $fieldName
     * @return Zend\Validator\ValidatorChain
     */
    public function getFor($fieldName){
         if( !isset($this->elements[$fieldName]) ){
             throw new \InvalidArgumentException("No existe el validator ". $fieldName);
         }
         return $this->elements[$fieldName];
    }
    
    /**
     *
     * @param string $field
     * @param array $messages
     */
    protected function addMessage($field, $messages){
        $this->messages[$field] = $messages;
    }
    
    /**
     * @return array
     */
    public function getMessages(){
        return $this->messages;
    }
    
    /**
     * @return array
     */
    public function toArray(){
        return $this->elements;
    }
    
}
