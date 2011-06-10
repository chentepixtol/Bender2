<?php
namespace Modules\Core\Schema;

use Symfony\Component\Yaml\Yaml;
use Application\Generator\File\FileCollection;
use Application\Generator\File\File;
use Application\Generator\Module\AbstractModule;
use Application\Database\Table;


/**
 *
 * @author chente
 *
 */
class Schema extends AbstractModule
{

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.Module::getName()
	 */
	public function getName(){
		return 'Schema';
	}

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.Module::getFiles()
	 */
	public function getFiles()
	{
		$files = new FileCollection();

		$files->append(
			new File('config/schema.yml',  Yaml::dump($this->createSchemaArray(), 3) )
		);

		return $files;
	}

	/**
	 *
	 * @return array
	 */
	protected function createSchemaArray()
	{
		$tables = $this->getBender()->getDatabase()->getTables();

		$schema = array( 'schema' => array());
		while ($tables->valid()) {
			$table = $tables->read();
			$tablename = $table->getName()->toString();
			$schema['schema'][$tablename] = array(
				'tablename' => $tablename,
			);
		}
		return $schema;
	}


}
