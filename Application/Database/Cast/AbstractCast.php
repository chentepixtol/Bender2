<?php

namespace Application\Database\Cast;

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
	 * @var unknown_type
	 */
	protected $lang;

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
	 * @return string
	 */
	abstract public function getType();

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
