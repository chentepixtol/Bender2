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
        		new InputArgument('project', InputArgument::REQUIRED, 'Proyecto'),
            	new InputArgument('module', InputArgument::IS_ARRAY, 'Modulos'),
        	));
	}

	/**
	 * (non-PHPdoc)
	 * @see Symfony\Component\Console\Command.Command::execute()
	 */
	public function execute(InputInterface $input, OutputInterface $output)
	{
		$bender = $this->getBender();
		$settings = $bender->getSettings();

		$project = $input->getArgument('project');

		if( $settings->hasAlias($project) ){
			$alias = $settings->getAlias($project);
			$project = $alias->get('project');
			$onlyModules = $alias->get('modules')->toArray();
		}
		else{
			$onlyModules = $input->getArgument('module');
		}

		if( empty($onlyModules) ){
			throw new \Exception('Not enough arguments.');
		}

		$this->getBender()->getConfiguration()->set('project', $project);

		$generator = new Generator();
		$modules = $bender->getModules($project);
		while ( $modules->valid() ) {
			$module = $modules->read();
			if( in_array($module->getName(), $onlyModules) ){
				$generator->addModule($module);
				$bender->getEventDispatcher()->addSubscriber($module->getSubscriber());
			}
		}
		$generator->generate();
	}

}