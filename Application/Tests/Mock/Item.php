<?php

namespace Application\Tests\Mock;

class Item
{
	private $id;

	private $value;

	public function __construct($id, $value){
		$this->setId($id);
		$this->setValue($value);
	}

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the $value
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param field_type $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param field_type $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}
}
