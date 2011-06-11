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
		if( !file_exists($file) ){
			throw new \Exception("No se ha definido el archivo settings.yml");
		}
		$this->settings = Yaml::load($file);
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
	 * @return array
	 */
	public function getEnconding(){
		return isset($this->settings['settings']['encoding']) ? $this->settings['settings']['encoding'] : 'utf-8';
	}

	/**
	 *
	 * @return Application\Config\Configuration
	 */
	public function getOptions(){
		if( null == $this->options ){
			$this->options = new Configuration($this->settings['settings']['options']);
		}
		return $this->options;
	}

}
