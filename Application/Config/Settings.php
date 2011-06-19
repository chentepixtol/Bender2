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
	 * file
	 * @var string
	 */
	protected $filename;

	/**
	 *
	 * @var Application\Config\Configuration
	 */
	protected $options;

	/**
	 *
	 * loaded
	 * @var boolean
	 */
	protected $isLoaded = false;

	/**
	 *
	 * construct
	 * @param string $file
	 * @throws \Exception
	 */
	public function __construct($file)
	{
		$this->filename = APPLICATION_PATH.'/'.$file;
		if( !file_exists($this->filename) ){
			throw new \Exception("No se ha definido el archivo settings.yml");
		}
	}

	/**
	 *
	 * @throws \Exception
	 */
	public function load()
	{
		$this->settings = Yaml::load($this->filename);
		$this->isLoaded = true;
	}

	/**
	 *
	 * @return array
	 */
	public function getConnectionParams(){
		return $this->get('db', array());
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
		if( null == $this->options ){
			$parameters = $this->get('options', array());
			$this->options = new Configuration($parameters);
		}
		return $this->options;
	}

	/**
	 *
	 * @return array
	 */
	public function getAlias($alias){
		$aliases = $this->get('alias');
		return $aliases[$alias];
	}

	/**
	 *
	 * has alias
	 * @param unknown_type $alias
	 * @return boolean
	 */
	public function hasAlias($alias){
		$aliases = $this->get('alias');
		return isset($aliases[$alias]);
	}

	/**
	 * @return boolean
	 */
	public function isLoaded() {
		return $this->isLoaded;
	}

	/**
	 *
	 * get
	 * @param string $index
	 * @param mixed $default
	 */
	private function get($index, $default = null)
	{
		if( !$this->isLoaded() ){
			$this->load();
		}
		return isset($this->settings['settings'][$index]) ? $this->settings['settings'][$index] : $default;
	}

}
