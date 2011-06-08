<?php
namespace Application\CLI;

use Application\Bender\Bender;

/**
 *
 *
 * @author chente
 *
 */
abstract class Command extends \Symfony\Component\Console\Command\Command
{

	/**
	 *
	 * @return Application\Bender\Bender
	 */
	public function getBender(){
		return Bender::getInstance();
	}

}