<?php
namespace Application\CLI;

use Symfony\Component\Console\Application;
use Application\Bender\Bender;

/**
 * Bender
 *
 * @author chentepixtol
 */
class CLI extends Application
{

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

}