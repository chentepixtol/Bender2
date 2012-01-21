{% include 'header.tpl' %}
{% set slug = Controller.getName().toSlug('newString').replace('-controller','') %}
{{ Controller.printNamespace() }}

use Zend\Form\Element;
use ZFriendly\Controller\ActionController;

{{ Catalog.printUse() }}
{{ Factory.printUse() }}
{{ Bean.printUse() }}
{{ Query.printUse() }}
{{ Form.printUse() }}

/**
 *
 * @author chente
 */
class {{ Controller }} extends ActionController
{

    /**
     *
     * @return array
     */
    public function indexAction(){
        return $this->forwardTo('{{ slug }}', 'list');
    }

    /**
     *
     * @return array
     */
    public function listAction()
    {
        ${{ collection }} = {{ Query }}::create()->find();
        $messages = $this->getFlashMessages();
        return compact('{{ collection }}', 'messages');
    }

    /**
     *
     * @return array
     */
    public function newAction()
    {
        $form = $this->getForm()->setAction(
            $this->generateUrl('{{ slug }}', 'create')
        );
        return compact('form');
    }

    /**
     *
     * @return array
     */
    public function editAction()
    {
        $id = $this->getRouteParam('id');
        ${{ bean }} = {{ Query }}::create()->primaryKey($id)
            ->findOneOrThrow("No existe el {{ Bean }} con id {$id}");

        $form = $this->getForm()
            ->populate(${{ bean }}->toArray())
            ->setAction(
                $this->generateUrl('{{ slug }}', 'update', 'crud', array('id' => $id))
            );

        $this->getView()->setTemplate("{{ slug }}/new.tpl");
        return compact('form');
    }

    /**
     *
     * @return array
     */
    public function createAction()
    {
        $form = $this->getForm();
        if( $this->getRequest()->isPost() ){

           $params = $this->getRequest()->post()->toArray();
           if( !$form->isValid($params) ){
               $this->getView()->setTemplate("{{ slug }}/new.tpl");
               return compact('form');
           }

           ${{ bean }} = {{ Factory }}::createFromArray($form->getValues());
           {{ Catalog }}::getInstance()->create(${{ bean }});

           $this->addFlashSuccess("Se ha guardado correctamente el {{ User }}");
        }
        $this->redirectTo('{{ slug }}', 'list');
    }

    /**
     *
     * @return array
     */
    public function updateAction()
    {
        $form = $this->getForm();
        if( $this->getRequest()->isPost() ){

            $params = $this->getRequest()->post()->toArray();
            if( !$form->isValid($params) ){
                $this->getView()->setTemplate("{{ slug }}/new.tpl");
                return compact('form');
            }

            $id = $this->getRouteParam('id');
            ${{ bean }} = {{ Query }}::create()->primaryKey($id)
                ->findOneOrThrow("No existe el {{ Bean }} con id {$id}");

            {{ Factory }}::populate(${{ bean }}, $form->getValues());
            {{ Catalog }}::getInstance()->update(${{ bean }});

            $this->addFlashSuccess("Se actualizo correctamente el {{ Bean}}");
        }
        $this->redirectTo('{{ slug }}', 'list');
    }

    /**
     *
     * @return {{ Form.getFullName() }}
     */
    protected function getForm()
    {
        $form = new {{ Form }}();
        $submit = new Element\Submit("send");
        $submit->setLabel("Guardar");
        $form->addElement($submit)->setMethod('post');
        $form->twitterDecorators();
        return $form;
    }

}
