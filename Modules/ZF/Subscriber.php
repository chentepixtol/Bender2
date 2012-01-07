<?php

namespace Modules\ZF;

use Application\Database\Cast\AbstractCast;

use Application\Generator\BaseClass;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Application\Bender\Bender;
use Application\Event\Event;


/**
 *
 * @author chente
 *
 */
class Subscriber implements EventSubscriberInterface
{

    /**
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        static $addSubscribed = null;
        if( !$addSubscribed ){
            $addSubscribed = true;
            return array(Event::LOAD_MODULES => 'onReady');
        }else{
            return array();
        }
    }

    /**
     *
     */
    public function onReady(Event $event){
        AbstractCast::register('php', "Modules\\ZF\\PHPCast");
    }

}