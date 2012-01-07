{% include 'header.tpl' %}
{% set BaseForm = classes.get('BaseForm') %}
{{ BaseForm.printNamespace() }}

use ZFriendly\Form\Twitter as TwitterForm;
use Zend\View\PhpRenderer;

/**
 *
 * {{ BaseForm }}
 * @author chente
 *
 */
class {{ BaseForm }} extends TwitterForm
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
        parent::init();
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
