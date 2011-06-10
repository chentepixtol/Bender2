<?php

namespace Application\Bender\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 *
 * @author chente
 *
 */
class EmptyEventSubscriber implements EventSubscriberInterface
{

	/**
	 *
	 * @return array
	 */
	public static function getSubscribedEvents(){
		return array();
	}

}