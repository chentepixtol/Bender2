<?php
namespace Application\CLI;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 *
 * @author chente
 *
 */
class Cache extends Command
{

	/**
	 * (non-PHPdoc)
	 * @see Symfony\Component\Console\Command.Command::configure()
	 */
	public function configure()
	{
		$this->setName('clear-cache')
        	->setDescription('Elimina el cache generado')
        	->setHelp("php bender clear-cache");
	}

	/**
	 * (non-PHPdoc)
	 * @see Symfony\Component\Console\Command.Command::execute()
	 */
	public function execute(InputInterface $input, OutputInterface $output)
	{
		$iterator = new \DirectoryIterator('cache');
		$exceptions = array('.', '..');
		foreach ($iterator as $fileInfo){
			if( $fileInfo->isDot() ){
				if( !in_array($fileInfo->getFilename(), $exceptions) ){
					@rmdir($fileInfo->getPathname());
				}
			}
			else{
				@unlink($fileInfo->getPathname());
			}
		}
		$output->writeln("Se ha borrado correctamente el cache");
	}

}