<?php

namespace Application\Config;

use Symfony\Component\Yaml\Yaml;
use Application\Native\String;
use Application\Config\Configuration;

class Settings
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
	 * @throws \Exception
	 */
	public function __construct($file)
	{
		$this->filename = $file;
		if( !file_exists($this->filename) ){
			throw new \Exception("El archivo no existe ".$this->filename);
		}
		$this->load();
	}

	/**
	 *
	 * @throws \Exception
	 */
	protected function load()
	{
		$this->configuration = new Configuration();
		$this->configuration->set('settings', Yaml::load($this->filename));
	}

	/**
	 *
	 * @return Application\Config\Configuration
	 */
	public function getConnectionParams(){
		return $this->get('db', new Configuration());
	}

	/**
	 *
	 * @return string
	 */
	public function getEnconding(){
		return $this->get('encoding', 'UTF-8');
	}

	/**
	 *
	 * @return string
	 */
	public function getOutputDir(){
		return $this->get('output_dir', 'output');
	}

	/**
	 *
	 * @return Application\Config\Configuration
	 */
	public function getOptions()
	{
		return $this->get('options', new Configuration());
	}

	/**
	 *
	 * @return Application\Config\Configuration
	 */
	public function getAlias($alias){
		return $this->get('alias')->get($alias);
	}

	/**
	 *
	 * has alias
	 * @param unknown_type $alias
	 * @return boolean
	 */
	public function hasAlias($alias){
		return $this->get('alias', new Configuration())->has($alias);
	}

	/**
	 *
	 * get
	 * @param string $index
	 * @param mixed $default
	 */
	public function get($index, $default = null)
	{
		if( ! $this->configuration->get('settings')->get('settings')->has($index) ){
			return $default;
		}
		return $this->configuration->get('settings')->get('settings')->get($index);
	}

}
