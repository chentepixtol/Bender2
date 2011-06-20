<?php


namespace Application\Config;

use Symfony\Component\Yaml\Yaml;
use Application\Native\String;
use Application\Config\Configuration;

class Schema extends Configuration
{

	/**
	 *
	 * file
	 * @var string
	 */
	protected $filename;

	/**
	 *
	 * construct
	 * @param string $file
	 * @throws \Exception
	 */
	public function __construct($file)
	{
		$this->filename = $file;
		if( !file_exists($this->filename) ){
			throw new \Exception("El archivo no existe ".$this->filename);
		}

		$this->set('schema', Yaml::load($this->filename));
	}

	/**
	 *
	 * @param string $tablename
	 * @return Application\Config\Configuration
	 */
	public function createConfiguration($tablename)
	{
		$configuration = new Configuration();
		if( $this->has('schema') && $this->get('schema')->has('schema') ){

			foreach ( $this->get('schema')->get('schema')->getParameters() as $object => $options ){
				if( $options->has('tablename') && $tablename == $options->get('tablename') ){
					$configuration->set('object', new String($object, String::UPPERCAMELCASE));
					$configuration->set('options', $options->get('options', array()));
					$configuration->set('extends', $options->get('extends', false));
					break;
				}
			}
		}
		return $configuration;
	}

}
