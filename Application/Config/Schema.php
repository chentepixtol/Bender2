<?php


namespace Application\Config;

use Symfony\Component\Yaml\Yaml;
use Application\Native\String;
use Application\Config\Configuration;

class Schema
{

	/**
	 *
	 * file
	 * @var string
	 */
	protected $filename;

	/**
	 *
	 * @var Application\Config\Configuration
	 */
	protected $configuration;

	/**
	 *
	 * construct
	 * @param string $file
	 * @throws \InvalidArgumentException
	 */
	public function __construct($file)
	{
		$this->filename = $file;
		if( !file_exists($this->filename) ){
			throw new \InvalidArgumentException("El archivo no existe ".$this->filename);
		}
		$this->configuration = new Configuration();
		$this->configuration->set('schema', Yaml::parse($this->filename));
	}

	/**
	 *
	 * @param string $tablename
	 * @return Application\Config\Configuration
	 */
	public function createConfiguration($tablename)
	{
		$configuration = new Configuration();
		if( $this->has('schema') ){

			foreach ( $this->get('schema')->getParameters() as $object => $options ){
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

	/**
	 * (non-PHPdoc)
	 * @see Application\Config.Configuration::get()
	 */
	protected function get($parameter, $default = null){
		return $this->configuration->get('schema')->get($parameter, $default);
	}

	/**
	 * (non-PHPdoc)
	 * @see Application\Config.Configuration::has()
	 */
	protected function has($parameter){
		return $this->configuration->get('schema')->has($parameter);
	}

}
