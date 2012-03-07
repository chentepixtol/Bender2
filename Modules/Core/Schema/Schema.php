<?php
namespace Modules\Core\Schema;

use Application\Native\String;

use Application\Config\Configuration;

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
        $options = $this->getBender()->getSettings()->getOptions();
        $prefixes = $options->get('schema_prefixes', new Configuration())->toArray();

        $schema = array( 'schema' => array());
        while ($tables->valid()) {
            $table = $tables->read();
            $tablename = $table->getName()->toString();
            $objectName = new String(str_replace($prefixes, '', $tablename), String::UNDERSCORE);
            $objectName = new String($objectName->toUpperCamelCase(), String::UPPERCAMELCASE);
            $schema['schema'][$objectName->singularize()] = array(
                'tablename' => $tablename,
            );
        }
        return $schema;
    }

    /**
     * (non-PHPdoc)
     * @see Application\Generator\Module.Module::getTemplateDirs()
     */
    public function getTemplateDirs(){
        $dir = __DIR__;
        $module  = $this->getName();
        return array($dir .'/templates', $dir . '/' . $module .'/templates');
    }

}
