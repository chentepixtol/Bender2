{% include 'header.tpl' %}
{% set BaseValidator = classes.get('BaseValidator') %}
{{ BaseValidator.printNamespace() }}

/**
 *
 * {{ BaseValidator }}
 * @author chente
 *
 */
abstract class {{ BaseValidator }}
{

    /**
     * 
     * @return array 
     */
    private $messages = array();

    /**
     * get Validators
     * @return array
     */
    abstract public function toArray();
    
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
    
}
