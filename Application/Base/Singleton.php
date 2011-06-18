<?php

namespace Application\Base;

/**
 *
 *
 * @author chente
 *
 */
abstract class Singleton
{

	/**
	 *
	 *
	 * @var unknown_type
	 */
    private static $instances = array();

    /**
     *
     *
     */
    final private function __construct(){}

    /**
     *
     *
     */
    final private function __clone(){}

    /**
     *
     *
     */
    final public static function getInstance(){
        $class = get_called_class();
        if( !isset(self::$instances[$class]) ){
            self::$instances[$class] = new static();
        }
        return self::$instances[$class];
    }
}