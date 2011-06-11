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
			Event::SAVE_FILES => 'onSaveFiles',
		);
	}

	/**
	 *
	 *
	 * @param Event $event
	 */
	public function onSaveFile(Event $event){
		static $i = 0;
		$i++;
		$this->getOutput()->writeln(sprintf("<info>  {$i}. %s</info>" , $event->getParameter('filename')));
	}

	/**
	 *
	 *
	 * @param Event $event
	 */
	public function onSaveFiles(Event $event){
		$this->getOutput()->writeln(sprintf("<info>%s</info>" , $event->getParameter('module')->getName()));
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