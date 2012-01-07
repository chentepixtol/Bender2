<?php

namespace Application\Database\Cast;

use Application\Native\String;

use Application\Database\Column;

/**
 *
 *
 * @author chente
 *
 */
abstract class AbstractCast
{
    /**
     *
     *
     * @var Column
     */
    protected $column;

    /**
     *
     *
     * @var string
     */
    protected $lang;

    /**
     *
     * @var array
     */
    protected static $casts = array();

    /**
     *
     * @return string
     */
    abstract public function getType();

    /**
     *
     *
     * @param Column $column
     * @param string $lang
     */
    public function __construct(Column $column, $lang){
        $this->setColumn($column);
        $this->setLang($lang);
    }

    /**
     *
     * @param unknown_type $lang
     * @param unknown_type $column
     * @return Application\Database\Cast\AbstractCast
     * @throws \Exception
     */
    public static function factory($lang, $column)
    {
        if( !isset(self::$casts[$lang]) ){
            throw new \Exception("El cast del languaje especificado no existe: ".$lang);
        }

        $className =  self::$casts[$lang];
        if( !class_exists($className)){
            throw new \Exception("La clase del languaje especificado no existe: ". $className);
        }

        return new $className($column, $lang);
    }

    /**
     *
     * @param string $lang
     * @param string $className
     */
    public static function register($lang, $className){
        self::$casts[$lang] = $className;
    }

    /**
     * @return Application\Database\Column
     */
    public function getColumn() {
        return $this->column;
    }

    /**
     * @param Application\Database\Column $column
     */
    public function setColumn(Column $column) {
        $this->column = $column;
    }

    /**
     * @return string
     */
    public function getLang() {
        return $this->lang;
    }

    /**
     * @param string $lang
     */
    public function setLang($lang) {
        $this->lang = $lang;
    }

    /**
     *
     * @return string
     */
    public function toString(){
        return $this->getType();
    }

    /**
     *
     * @return string
     */
    public function __toString(){
        return $this->toString();
    }

}
