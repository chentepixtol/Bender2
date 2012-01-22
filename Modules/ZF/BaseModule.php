<?php

namespace Modules\ZF;

use Application\Config\Configuration;

use Application\Native\String;
use Application\Database\Table;
use Application\Generator\Module\AbstractModule;

abstract class BaseModule extends AbstractModule
{

    /**
     * (non-PHPdoc)
     * @see Application\Generator\Module.Module::getTemplateDirs()
     */
    public function getTemplateDirs(){
        $dir = __DIR__;
        $module  = $this->getName();
        return array($dir .'/templates', $dir . '/' . $module .'/templates');
    }

    /**
     *
     * @param Table $table
     */
    public function shortcuts(Table $table)
    {
        $classes = $this->getBender()->getClasses();

        $this->getView()->table = $table;
        $this->getView()->parent = $table->getParent();
        $this->getView()->fields = $table->getColumns();
        $this->getView()->fullFields = $table->getFullColumns();
        $this->getView()->foreignKeys = $table->getForeignKeys();
        $this->getView()->primaryKey = $table->getPrimaryKey();
        $this->getView()->Bean = $bean = $classes->get($table->getObject()->toString());
        $this->getView()->bean = $bean->getName()->toCamelCase();
        $this->getView()->isZF2 = $this->isZF2();
        $this->getView()->globalOptions = $globalOptions = $this->getBender()->getSettings()->get('options', new Configuration());
        $this->getView()->meta = $globalOptions->get('meta', new Configuration());

        $sufixes = array('Collection', 'Factory', 'Catalog', 'Exception',
                         'Query', 'Form', 'Validator', 'Filter', 'Controller');
        foreach( $sufixes as $suffix ){
            $this->addShorcutBySuffix($table, $suffix);
        }
    }

    /**
     * @return string
     */
    public function getApplicationNamespace()
    {
        static $applicationNamespace = null;
        if( null == $applicationNamespace ){
            $ns = $this->getProjectOptions()->get('application_namespace', 'Application');
            $ns = preg_replace("/\\\\$/", '', $ns) . '\\';
            $applicationNamespace = str_replace('\\', '/', $ns);
        }
        return $applicationNamespace;
    }

    /**
     *
     * @param Table $table
     * @param string $suffix
     */
    protected function addShorcutBySuffix(Table $table, $suffix = '')
    {
        $variableName = $table->getObject()->toCamelCase();
        $className = $table->getObject()->toUpperCamelCase();
        $classes = $this->getBender()->getClasses();
        $varname = new String($suffix, String::UPPERCAMELCASE);
        $this->getView()->assign($suffix, $classes->get($className.$suffix));
        $this->getView()->assign($varname->toCamelCase(), $variableName.$suffix);
    }

    /**
     * (non-PHPdoc)
     * @see Application\Generator\Module.Module::getSubscriber()
     */
    public function getSubscriber(){
        return new Subscriber();
    }

    /**
     * @return \Application\Config\Configuration
     */
    protected function getProjectOptions(){
        return $this->getBender()->getSettings()->get('ZF');
    }

    /**
     * @return int
     */
    protected function getZFVersion(){
        return $this->getProjectOptions()->get('zf_version', 1);
    }

    /**
     * @return boolean
     */
    protected function isZF2(){
        return $this->getZFVersion() == 2;
    }

}
