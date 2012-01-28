<?php

namespace Application\Database;

use Application\Base\Collectable;

use Application\Database\Table;
use Application\Database\Column;

class ForeignKey implements Collectable
{

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var Application\Database\Column
     */
    protected $local;

    /**
     *
     * @var Application\Database\Column
     */
    protected $foreign;

    /**
     *
     * @var Application\Database\Table
     */
    protected $localTable;

    /**
     *
     * @var Application\Database\Table
     */
    protected $foreignTable;

    /**
     *
     * @param string $name
     * @param Application\Database\Column $local
     * @param Application\Database\Column $foreign
     * @param Application\Database\Table $localTable
     * @param Application\Database\Table $foreignTable
     */
    public function __construct($name, $local, $foreign, $localTable, $foreignTable)
    {
        $this->setName($name);
        $this->setLocal($local);
        $this->setForeign($foreign);
        $this->setLocalTable($localTable);
        $this->setForeignTable($foreignTable);
    }

    /**
     * (non-PHPdoc)
     * @see Application\Base.Collectable::getIndex()
     */
    public function getIndex(){
        return $this->getName();
    }

    /**
     * @return Application\Database\Column
     */
    public function getLocal() {
        return $this->local;
    }

    /**
     * @return Application\Database\Column
     */
    public function getForeign() {
        return $this->foreign;
    }

    /**
     * @return Application\Database\Table
     */
    public function getLocalTable() {
        return $this->localTable;
    }

    /**
     * @return Application\Database\Table
     */
    public function getForeignTable() {
        return $this->foreignTable;
    }

    /**
     * @param Column $local
     */
    public function setLocal($local) {
        $this->local = $local;
    }

    /**
     * @param Column $foreign
     */
    public function setForeign($foreign) {
        $this->foreign = $foreign;
    }

    /**
     * @param Table $localTable
     */
    public function setLocalTable($localTable) {
        $this->localTable = $localTable;
    }

    /**
     * @param Table $foreignTable
     */
    public function setForeignTable($foreignTable) {
        $this->foreignTable = $foreignTable;
    }

    /**
     * @return the $name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     *
     * @return string
     */
    public function __toString(){
        $text = "FOREIGN KEY ( {$this->local->getName()->toString()} ) ";
        $text .="REFERENCES {$this->foreignTable->getName()}";
        $text .="( {$this->foreign->getName()->toString()} )";
        return $text;
    }

}
