<?php
namespace Application\CLI;

use Application\Bender\Event\Event;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Application\Generator\Module\Module;
use Application\Generator\Generator;


/**
 *
 *
 * @author chente
 *
 */
class Create extends Command
{

	/**
	 * (non-PHPdoc)
	 * @see Symfony\Component\Console\Command.Command::configure()
	 */
	public function configure()
	{
		$this->setName('create')
        	->setDescription('Genera el codigo para los distintos modulos')
        	->setHelp("php bender create modules Schema")
        	->setDefinition(array(
            	new InputArgument('module', InputArgument::IS_ARRAY, 'Modulos'),
        	));
	}

	/**
	 * (non-PHPdoc)
	 * @see Symfony\Component\Console\Command.Command::execute()
	 */
	public function execute(InputInterface $input, OutputInterface $output)
	{
		$onlyModules = $input->getArgument('module');
		if( empty($onlyModules) ){
			throw new \Exception('Not enough arguments.');
		}

		$generator = new Generator();
		$modules = $this->getBender()->getModules();
		while ( $modules->valid() ) {
			$module = $modules->read();
			if( in_array($module->getName(), $onlyModules) ){
				$generator->addModule($module);
				$this->getBender()->getEventDispatcher()->addSubscriber($module->getSubscriber());
			}
		}
		$generator->generate();
	}

}