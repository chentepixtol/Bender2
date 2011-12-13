<?php

namespace Application\Database;

use Application\Native\String;
use Application\Database\Cast\AbstractCast;
use Doctrine\DBAL\Schema\Column as DoctrineColumn;
use Application\Base\Collectable;

/**
 *
 *
 * @author chente
 *
 */
class Column implements Collectable
{

	/**
	 *
	 *
	 * @var Doctrine\DBAL\Schema\Column
	 */
	protected $doctrineColumn;

	/**
	 *
	 * @var Table
	 */
	protected $table;

	/**
	 *
	 * @var Application\Native\String
	 */
	protected $name;

	/**
	 *
	 * @var boolean
	 */
	protected $isPrimaryKey = false;

	/**
	 *
	 * @var boolean
	 */
	protected $isUnique = false;

	/**
	 *
	 * @var Application\Database\Cast\AbstractCast
	 */
	protected $cast;

	/**
	 *
	 *
	 * @param Doctrine\DBAL\Schema\Column $column
	 */
	public function __construct(DoctrineColumn $column){
		$this->doctrineColumn = $column;
	}

	/**
	 * (non-PHPdoc)
	 * @see Application\Base.Collectable::getIndex()
	 */
	public function getIndex(){
		return $this->getName()->toString();
	}

	/**
	 *
	 * @return string
	 */
	public function toString(){
		return $this->getName()->toString();
	}

	/**
	 *
	 * @return string
	 */
	public function __toString(){
		return $this->toString();
	}

	/**
	 *
	 * @return Application\Native\String
	 */
	public function getName(){
		if( null == $this->name ){
			$this->name = new String($this->doctrineColumn->getName(), String::UNDERSCORE);
		}
		return $this->name;
	}

	/**
	 *
	 * @return string
	 */
	public function getter(){
		return 'get'.$this->getName()->toUpperCamelCase();
	}

	/**
	 *
	 * @return string
	 */
	public function setter(){
		return 'set'.$this->getName()->toUpperCamelCase();
	}

	/**
	 *
	 *
	 * @param string $lang
	 * @return Application\Database\Cast\AbstractCast
	 */
	public function cast($lang)
	{
		$class = new String($lang, String::SLUG);
		$className = 'Application\\Database\\Cast\\'.$class->toUpperCamelCase();

		if( !class_exists($className)){
			throw new \Exception("El cast del languaje especificado no existe");
		}

		if( ( null != $this->cast && $lang != $this->cast->getLang() ) || null == $this->cast ){
			$this->cast = new $className($this, $lang);
		}

		return $this->cast;
	}

	/**
	 * @return boolean
	 */
	public function isPrimaryKey() {
		return $this->isPrimaryKey;
	}

	/**
	 * @param boolean $isPrimaryKey
	 */
	public function setIsPrimaryKey($isPrimaryKey) {
		$this->isPrimaryKey = $isPrimaryKey;
	}

	/**
	 * @return Table
	 */
	public function getTable() {
		return $this->table;
	}

	/**
	 * @param Table $table
	 */
	public function setTable(Table $table) {
		$this->table = $table;
	}

	/**
	 * @param boolean $isUnique
	 */
	public function setIsUnique($isUnique) {
		$this->isUnique = $isUnique;
	}

	/**
	 *
	 * @return boolean
	 */
	public function isUnique(){
		return $this->isUnique;
	}

	/**
	 *
	 * @return boolen
	 */
	public function getAutoincrement()
    {
        return $this->doctrineColumn->getAutoincrement();
    }

    /**
     *
     * @return mixed
     */
	public function getType()
    {
        return $this->doctrineColumn->getType()->getName();
    }

    /**
     *
     * @return boolean
     */
    public function isBigint(){
    	return $this->getType() == 'bigint';
    }

	/**
     *
     * @return boolean
     */
    public function isBoolean(){
    	return $this->getType() == 'boolean';
    }

	/**
     *
     * @return boolean
     */
    public function isDatetime(){
    	return in_array($this->getType(), array('datetime', 'datetimez'));
    }

	/**
     *
     * @return boolean
     */
    public function isDate(){
    	return $this->getType() == 'date';
    }

	/**
     *
     * @return boolean
     */
    public function isTime(){
    	return $this->getType() == 'time';
    }

	/**
     *
     * @return boolean
     */
    public function isDecimal(){
    	return $this->getType() == 'decimal';
    }

    /**
     *
     * @return boolean
     */
    public function isInteger(){
    	return $this->getType() == 'integer';
    }

	/**
     *
     * @return boolean
     */
    public function isSmallint(){
    	return $this->getType() == 'smallint';
    }

	/**
     *
     * @return boolean
     */
    public function isString(){
    	return $this->getType() == 'string';
    }

	/**
     *
     * @return boolean
     */
    public function isText(){
    	return $this->getType() == 'text';
    }

	/**
     *
     * @return boolean
     */
    public function isFloat(){
    	return $this->getType() == 'float';
    }

    /**
	 *
	 * TODO getLength
	 * @return int
	 */

    /**
	 * TODO getUnsigned
	 * @return boolen
	 */


    /**
	 * TODO getComment
	 * @return strin
	 */

    /**
	 *
	 * @return boolen
	 */
    public function getNotnull()
    {
        return $this->doctrineColumn->getNotnull();
    }

    /**
     * alias for not null
     * @return boolen
     */
    public function isRequired(){
    	return $this->getNotnull();
    }


    /**
	 *
	 * @return mixed
	 */
    public function getDefault()
    {
        return $this->doctrineColumn->getDefault();
    }

}