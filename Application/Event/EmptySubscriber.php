<?php

namespace Application\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 *
 * @author chente
 *
 */
class EmptySubscriber implements EventSubscriberInterface
{

    /**
     *
     * @return array
     */
    public static function getSubscribedEvents(){
        return array();
    }

}