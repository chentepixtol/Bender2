<?php

namespace Application\Native;

/**
 *
 *
 * @author chente
 * @method string toUnderscore
 * @method string toUpperCamelCase
 * @method string toCamelCase
 * @method string toUpperCase
 * @method string toSlug
 */
class String
{

	/**
	 *
	 * @var string
	 */
	const UNDERSCORE = 'Underscore';
	const UPPERCAMELCASE = 'UpperCamelCase';
	const CAMELCASE = 'CamelCase';
    const UPPERCASE = 'UpperCase';
    const SLUG = 'Slug';

	/**
	 *
	 *
	 * @var string
	 */
	private $string;

	/**
	 *
	 * @var string
	 */
	private $mode;

	/**
	 *
	 *
	 * @param string $string
	 */
	public function __construct($string, $mode = self::UNDERSCORE){
		$this->string = $string;
		$this->mode = $mode;
	}

	/**
	 *
	 *
	 */
	public function __toString(){
		return $this->toString();
	}

	/**
	 *
	 *
	 */
	public function toString(){
		return $this->string;
	}

	/**
	 *
	 * @param string $string
	 * @return boolean
	 */
	public function equal($string){
		return $this->string == $string;
	}

	/**
	 *
	 * @param string $method
	 * @param array $args
	 * @return string
	 * @throws \InvalidArgumentException
	 */
	public function __call($method, $args)
	{
		$method = str_replace('to', '', $method);
		if( $this->mode == $method ){
			return $this->string;
		}

		$internalMethod = lcfirst($this->mode).'To'.$method;
		if( !method_exists($this, $internalMethod) ){
			throw new \InvalidArgumentException("El metodo ". $internalMethod. " no existe");
		}

		return $this->{$internalMethod}();
	}

    /**
     * MY_PUBLIC_VAR >>  myPubliVar
     * @return string
     */
    protected function upperCaseToCamelCase()
    {
      return $this->_toCamelCase('_', false, true);
    }

    /**
     * MY_PUBLIC_VAR >> MyPublicVar
     * @param string $this->string
     * @return string
     */
    protected function upperCaseToUpperCamelCase()
    {
      return $this->_toCamelCase('_', true, true);
    }

    /**
     * MY_PUBLIC_VAR >> my_protected_var
     * @param string $this->string
     * @return string
     */
    protected function upperCaseToUnderScore()
    {
      return strtolower($this->string);
    }

    /**
     * MY_PUBLIC_VAR >> my-protected-var
     * @param string $this->string
     * @return string
     */
    protected function upperCaseToSlug()
    {
      return str_replace('_','-',strtolower($this->string));
    }

    /**
     * myPublicVar >> MY_PUBLIC_VAR
     * @param string $this->string
     * @return string
     */
    protected function camelCaseToUpperCase()
    {
      return strtoupper($this->toSeparatedString('_'));
    }

    /**
     * myPublicVar >> my_protected_var
     * @param string $this->string
     * @return string
     */
    protected function camelCaseToUnderscore()
    {
      return $this->toSeparatedString('_');
    }

    /**
     * myPublicVar >> MyPublicVar
     * @param string $this->string
     * @return string
     */
    protected function camelCaseToUpperCamelCase()
    {
      return ucfirst($this->string);
    }

    /**
     * myPublicVar >> my-protected-var
     * @param string $this->string
     * @return string
     */
    protected function camelCaseToSlug()
    {
      return $this->toSeparatedString('-');
    }

    /**
     * MyPublicVar >> myPublicVar
     * @param string $this->string
     * @return string
     */
    protected function upperCamelCaseToCamelCase()
    {
      return lcfirst($this->string);
    }

    /**
     * MyPublicVar >> MY_PUBLIC_VAR
     * @param string $this->string
     * @return string
     */
    protected function upperCamelCaseToUpperCase()
    {
      return strtoupper($this->toSeparatedString('_'));
    }

    /**
     * MyPublicVar >> my_protected_var
     * @param string $this->string
     * @return string
     */
    protected function upperCamelCaseToUnderScore()
    {
      return $this->toSeparatedString('_');
    }

    /**
     * MyPublicVar >> my-protected_var
     * @param string $this->string
     * @return string
     */
    protected function upperCamelCaseToSlug()
    {
      return $this->toSeparatedString('-');
    }

    /**
     * my_protected_var >> myPublicVar
     * @param string $this->string
     * @return string
     */
    protected function underScoreToCamelCase()
    {
      return $this->_toCamelCase('_',false);
    }

    /**
     * my_protected_var >> MyPublicVar
     * @param string $this->string
     * @return string
     */
    protected function underscoreToUpperCamelCase()
    {
      	return $this->_toCamelCase('_',true);
    }

    /**
     * my_protected_var >> MY_PUBLIC_VAR
     * @param string $this->string
     * @return string
     */
    protected function underScoreToUpperCase()
    {
      return strtoupper($this->string);
    }

    /**
     * my_protected_var >> my-protected-var
     * @param string $this->string
     * @return string
     */
    protected function underScoreToSlug()
    {
      return str_replace('_','-',$this->string);
    }

    /**
     * my-protected-var >> MY_PUBLIC_VAR
     * @param string $this->string
     * @return string
     */
    protected function slugToUpperCase()
    {
      return str_replace('-','_',strtoupper($this->string));
    }

    /**
     * my-protected-var >> MyPublicVar
     * @param string $this->string
     * @return string
     */
    protected function slugToUpperCamelCase()
    {
      return $this->_toCamelCase('-',true);
    }

    /**
     * my-protected-var >> myPublicVar
     * @param string $this->string
     * @return string
     */
    protected function slugToCamelCase()
    {
      return $this->_toCamelCase('-',false);
    }

    /**
     * my-protected-var >> my_protected_var
     * @param string $this->string
     * @return string
     */
    protected function slugToUnderScore()
    {
      return str_replace('-','_',$this->string);
    }

    /**
     * Convierte un string a camelCase
     * @param string $this->string (un-string/un_string/un.string)
     * @param string $separator [OPTIONAL]
     * @param boolean $first [OPTIONAL]
     */
    private function _toCamelCase($separator = '-', $first = false, $toLower = false)
    {
    	$string = $toLower ? strtolower($this->string) : $this->string;
        $parts = explode($separator, $string);
        $newString = '';
        $i = 0;
        foreach ( $parts as $part )
        {
            if ($i == 0 && ! $first)
                $newString = $part;
            else
                $newString .= ucfirst($part);
            $i ++;
        }

        return $newString;
    }

    /**
     * Convierte un sring en camel case a uno separado por [separator]
     * @param string $separator
     */
    private function toSeparatedString($separator = '-')
    {
    	$newString = lcfirst($this->string);
      	$func = create_function('$c', 'return "'.$separator.'" . strtolower($c[1]);');
      	return preg_replace_callback('/([A-Z])/', $func, $newString);
    }




}

