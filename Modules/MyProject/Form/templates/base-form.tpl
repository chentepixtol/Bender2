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
     * init
     */
    public function init(){
        $this->setView(new PhpRenderer());
    }
    
}
