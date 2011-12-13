{% include 'header.tpl' %}
{% set BaseForm = classes.get('BaseForm') %}
{{ BaseForm.printNamespace() }}

use Zend\Form\Form;
use Zend\View\PhpRenderer;

/**
 *
 * {{ BaseForm }}
 * @author chente
 *
 */
class {{ BaseForm }} extends Form
{

    /**
     * @var {{ classes.get('BaseValidator') }} $validator
     */
    protected $validator;
    
    /**
     * @var {{ classes.get('BaseFilter') }} $filter
     */
    protected $filter;
    
    /**
     * @var array
     */ 
    protected $elements = array();

    /**
     * init
     */
    public function init(){
        $this->setView(new PhpRenderer());
    }
    
    /**
     * @param string $fieldName
     * @return Zend\Form\Element
     */
    public function getFor($fieldName){
         if( !isset($this->elements[$fieldName]) ){
             throw new \InvalidArgumentException("No existe el elemento ". $fieldName);
         }
         return $this->elements[$fieldName];
    }
   
}
