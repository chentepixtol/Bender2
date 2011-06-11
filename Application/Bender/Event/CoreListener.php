<?php

namespace Application\Bender\Event;

use Application\Bender\Bender;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 *
 * @author chente
 *
 */
class CoreListener implements EventSubscriberInterface
{

	/**
	 *
	 * @return array
	 */
	public static function getSubscribedEvents(){
		return array(
			Event::SAVE_FILE => 'onSaveFile',
		);
	}

	/**
	 *
	 *
	 * @param Event $event
	 */
	public function onSaveFile(Event $event){
		$this->getOutput()->writeln(sprintf("<info>Save ... %s</info>" , $event->getParameter('filename')));
	}

	/**
	 *
	 * @return Application\Bender\Bender
	 */
	public function getBender(){
		return Bender::getInstance();
	}

	/**
	 *
	 * @return Symfony\Component\Console\Output\ConsoleOutput
	 */
	public function getOutput(){
		return $this->getBender()->getCLI()->getOutput();
	}


}