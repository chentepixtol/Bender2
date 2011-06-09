<?php


namespace Application\Config;

use Symfony\Component\Yaml\Yaml;
use Application\Native\String;
use Application\Config\Configuration;

class Schema
{

	/**
	 *
	 * @var array
	 */
	protected $schema = array();

	/**
	 *
	 *
	 * @param string $file
	 * @throws \Exception
	 */
	public function load($file)
	{
		if( file_exists($file) ){
			$this->schema = Yaml::load($file);
		}
	}


	/**
	 *
	 * @param string $tablename
	 * @return Application\Config\Configuration
	 */
	public function createConfiguration($tablename)
	{
		$configuration = new Configuration();
		if( isset($this->schema['schema']) ){
			foreach ($this->schema['schema'] as $object => $options ){
				if( isset($options['tablename']) && $tablename == $options['tablename'] ){
					$configuration->set('object', new String($object, String::UPPERCAMELCASE));
					$configuration->set('options', isset($options['options']) ? $options['options'] : array());
					$configuration->set('extends', isset($options['extends']) ? $options['extends'] : false);
					break;
				}
			}
		}
		return $configuration;
	}

}
