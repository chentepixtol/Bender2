<?php

namespace Application\CLI;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Application;

/**
 * Bender
 *
 * @author chentepixtol
 */
class CLI extends Application
{

	/**
	 *
	 *
	 * @var Symfony\Component\Console\Output\ConsoleOutput
	 */
	protected $output;

	/**
	 * (non-PHPdoc)
	 * @see Symfony\Component\Console.Application::run()
	 */
	public function run(InputInterface $input = null, OutputInterface $output = null){
		if( null == $output ){
			$output = $this->getOutput();
		}
		$this->output = $output;
		parent::run($input, $output);
	}

    /**
     *
     */
    public function __construct()
    {
        parent::__construct('Welcome to Bender', '2.0');
    }

    /**
     *
     *
     */
    public function loadCommands()
    {
        $create = new Create();
        $this->addCommands(array(
        	$create,
        ));
    }

	/**
	 *
	 * @return Symfony\Component\Console\Output\ConsoleOutput
	 */
	public function getOutput(){
		if( null == $this->output ){
			$this->output =  new ConsoleOutput();
		}
		return $this->output;
	}

}