<?php

namespace Application\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Application\Bender\Bender;


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
			Event::VIEW_INIT => 'onViewInit',
		);
	}

	/**
	 *
	 * @param Event $event
	 */
	public function onViewInit(Event $event){
		$view = $event->get('view');
		$view->addGlobal('routes', $this->getBender()->getRoutes());
	}

	/**
	 *
	 *
	 * @param Event $event
	 */
	public function onSaveFile(Event $event){
		static $i = 0; $i++;
		$this->getOutput()->writeln(sprintf("<info>  {$i}. %s</info>" , $event->get('filename')));
	}

	/**
	 *
	 *
	 * @param Event $event
	 */
	public function onSaveFiles(Event $event){
		$this->getOutput()->writeln(sprintf("<info>%s</info>" , $event->get('module')->getName()));
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
		return $this->getBender()->getContainer()->get('output');
	}


}