<?php
namespace Application\Generator\Module;

use Symfony\Component\Finder\Finder as sfFinder;

/**
 *
 * @author chente
 *
 */
class Finder
{
    /**
     *
     * @var sfFinder
     */
    protected $sfFinder;

    /**
     *
     *
     * @var Application\Generator\Module\ModuleCollection
     */
    protected $modules;

    /**
     *
     * @var array
     */
    protected $directories;

    /**
     *
     *
     */
    public function __construct($directories)
    {
        if( empty($directories) ){
            throw new \Exception("No existen los directorios");
        }

        $this->sfFinder = new sfFinder();
        $this->directories = (array) $directories;
    }

    /**
     *
     *
     */
    protected function find()
    {
        $this->modules = new ModuleCollection();

        $iterator = $this->sfFinder->files()->name('*.php')->in($this->directories)->getIterator();
        foreach ($iterator as $file){
            require_once $file;
            $className = str_replace('.php', '', $file->getPathName());
            $namespaceClassName = str_replace('/', '\\', substr($className, strpos($className, 'Modules')));
            if( class_exists($namespaceClassName) ){
                $reflectionClass = new \ReflectionClass($namespaceClassName);

                if( $reflectionClass->implementsInterface('Application\\Generator\\Module\\Module')
                    && !$reflectionClass->isAbstract()){
                    $module = $reflectionClass->newInstance();
                    $this->modules->append($module);
                }
            }
        }
    }

    /**
     *
     * @return Application\Generator\Module\ModuleCollection
     */
    public function findModules(){
        if( !($this->modules instanceof ModuleCollection) ){
            $this->find();
        }
        $this->modules->rewind();
        return $this->modules;
    }

}