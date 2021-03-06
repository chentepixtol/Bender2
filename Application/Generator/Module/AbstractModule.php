<?php

namespace Application\Generator\Module;

use Application\Bender\View;
use Application\Bender\Bender;
use Application\Generator\File\FileCollection;
use Application\Database\TableCollection;
use Application\Event\EmptySubscriber;

abstract class AbstractModule implements Module
{

    /**
     * (non-PHPdoc)
     * @see Application\Generator\Module.Module::init()
     */
    public function init(){

    }

    /**
     *
     * @return Application\Bender\View
     */
    public function getView(){
        return $this->getBender()->getView($this);
    }

    /**
     *
     * @return Application\Bender\Bender
     */
    public function getBender(){
        return Bender::getInstance();
    }

    /**
     * (non-PHPdoc)
     * @see Application\Base.Collectable::getIndex()
     */
    public function getIndex(){
        return $this->getName();
    }

    /**
     * (non-PHPdoc)
     * @see Application\Generator\Module.Module::getSubscriber()
     */
    public function getSubscriber(){
        return new EmptySubscriber();
    }

}