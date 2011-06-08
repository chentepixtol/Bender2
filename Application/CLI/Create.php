<?php
namespace Application\CLI;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Application\Generator\Module\Finder;
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
		$modulesParams = $input->getArgument('module');
		if( empty($modulesParams) ){
			throw new \Exception('Not enough arguments.');
		}

		$directories = $this->getBender()->getConfiguration()->getParameter('modulesPath');

		$generator = new Generator();
		$finder = new Finder($directories);
		$finder->getModules()->each(function(Module $module) use ($generator, $modulesParams){
			if( in_array($module->getName(), $modulesParams) ){
				$generator->addModule($module);
			}
		});

		$generator->generate();
	}

}