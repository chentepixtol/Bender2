<?php

namespace Application\Config;

use Symfony\Component\Yaml\Yaml;
use Application\Native\String;

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
     * @throws \InvalidArgumentException
     */
    public function __construct($file)
    {
        $this->filename = $file;
        if( !file_exists($this->filename) ){
            throw new \InvalidArgumentException("El archivo no existe ".$this->filename);
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
        $this->configuration->set('settings', Yaml::parse($this->filename));
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
        if( !$this->getRootConfiguration()->has($index) ){
            return $default;
        }
        return $this->getRootConfiguration()->get($index);
    }

    /**
     *
     * @param string $dotNotation
     * @param mixed $default
     */
    public function getByDotNotation($dotNotation, $default = null){
        return $this->getRootConfiguration()->getByDotNotation($dotNotation, $default);
    }

    /**
     *
     * @return Configuration
     */
    protected function getRootConfiguration(){
        return $this->configuration->get('settings')->get('settings');
    }

}
