{% include 'header.tpl' %}
{% set BaseForm = classes.get('BaseForm') %}
{{ BaseForm.printNamespace() }}

use ZFriendly\Form\Twitter as TwitterForm;
{% if isZF2 %}
use Zend\View\PhpRenderer as ZendView;
{% else %}
use Zend_View as ZendView;
{% endif %}


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
        $this->setView(new ZendView());
    }
    
    /**
     * @param string $fieldName
{% if isZF2 %}
     * @return \Zend\Form\Element
{% else %}
     * @return \Zend_Form_Element
{% endif %}
     */
    public function getFor($fieldName){
         if( !isset($this->elements[$fieldName]) ){
             throw new \InvalidArgumentException("No existe el elemento ". $fieldName);
         }
         return $this->elements[$fieldName];
    }
   
}
