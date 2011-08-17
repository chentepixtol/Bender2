<?php

namespace Application\CLI;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Application;
use Application\Event\Event;

/**
 * Bender
 *
 * @author chentepixtol
 */
class CLI extends Application
{

	/**
	 *
	 * @var Symfony\Component\EventDispatcher\EventDispatcher
	 */
	protected $eventDispatcher;

    /**
     *
     * @param Symfony\Component\EventDispatcher\EventDispatcher
     */
    public function __construct(EventDispatcher $eventDispatcher)
    {
        parent::__construct('Welcome to Bender', '2.0');
        $this->eventDispatcher = $eventDispatcher;
        $this->loadCommands();
        $this->eventDispatcher->dispatch(Event::CLI_READY, new Event(array('cli' => $this)));
    }

    /**
     *
     *
     */
    private function loadCommands()
    {
        $this->addCommands(array(
        	new Create(),
        	new Cache(),
        ));
    }
}