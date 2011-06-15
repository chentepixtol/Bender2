<?php

namespace Application\Config;

use Symfony\Component\Yaml\Yaml;
use Application\Native\String;
use Application\Config\Configuration;

class Settings
{

	/**
	 *
	 * @var array
	 */
	protected $settings = array();

	/**
	 *
	 * @var Application\Config\Configuration
	 */
	protected $options;

	/**
	 *
	 *
	 * @param string $file
	 * @throws \Exception
	 */
	public function load($file)
	{
		$fileName = APPLICATION_PATH .'/' . $file;
		if( !file_exists($fileName) ){
			throw new \Exception("No se ha definido el archivo settings.yml");
		}
		$this->settings = Yaml::load($fileName);
	}

	/**
	 *
	 * @return array
	 */
	public function getConnectionParams(){
		return $this->settings['settings']['db'];
	}

	/**
	 *
	 * @return string
	 */
	public function getEnconding(){
		return isset($this->settings['settings']['encoding']) ? $this->settings['settings']['encoding'] : 'UTF-8';
	}

	/**
	 *
	 * @return string
	 */
	public function getOutputDir(){
		return isset($this->settings['settings']['output_dir']) ? $this->settings['settings']['output_dir'] : 'output';
	}

	/**
	 *
	 * @return Application\Config\Configuration
	 */
	public function getOptions()
	{
		if( null == $this->options ){
			$parameters = isset($this->settings['settings']['options']) ? $this->settings['settings']['options'] : array();
			$this->options = new Configuration($parameters);
		}
		return $this->options;
	}

	/**
	 *
	 * @return array
	 */
	public function getAlias($alias){
		return $this->settings['settings']['alias'][$alias];
	}

	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $alias
	 * @return boolean
	 */
	public function hasAlias($alias){
		return isset($this->settings['settings']['alias'][$alias]);
	}

}
