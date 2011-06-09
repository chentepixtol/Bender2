<?php

namespace Application\Native;

/**
 *
 *
 * @author chente
 *
 */
class String
{

	/**
	 *
	 * @var string
	 */
	const UNDERSCORE = 'underscore';
	const UPPERCAMELCASE = 'uppercamelcase';

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
	 * @return string
	 */
	public function toUpperCamelCase(){
		if( $this->mode == self::UPPERCAMELCASE ) return $this->string;
		$method = $this->mode . '_to_' . self::UPPERCAMELCASE;
		return $this->{$method}();
	}

    /**
     * MY_PUBLIC_VAR >>  myPubliVar
     * @return string
     */
    protected function upperCaseToCammelCase()
    {
      return $this->_toCamelCase(strtolower($this->string),'_',false);
    }

    /**
     * MY_PUBLIC_VAR >> MyPublicVar
     * @param string $this->string
     * @return string
     */
    protected function upperCaseToUpperCammelCase()
    {
      return $this->_toCamelCase(strtolower($this->string),'_',true);
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
      return strtoupper($this->toSeparatedString($this->string,'_'));
    }

    /**
     * myPublicVar >> my_protected_var
     * @param string $this->string
     * @return string
     */
    protected function camelCaseToUnderscore()
    {
      return $this->toSeparatedString($this->string,'_');
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
      return $this->toSeparatedString($this->string,'-');
    }

    /**
     * MyPublicVar >> myPublicVar
     * @param string $this->string
     * @return string
     */
    protected function upperCamelCaseToCamelCase()
    {
      $this->string{0} = strtolower($this->string{0});
      return $this->string;
    }

    /**
     * MyPublicVar >> MY_PUBLIC_VAR
     * @param string $this->string
     * @return string
     */
    protected function upperCamelCaseToUpperCase()
    {
      return strtoupper($this->toSeparatedString($this->string,'_'));
    }

    /**
     * MyPublicVar >> my_protected_var
     * @param string $this->string
     * @return string
     */
    protected function upperCamelCaseToUnderScore()
    {
      return $this->toSeparatedString($this->string,'_');
    }

    /**
     * MyPublicVar >> my-protected_var
     * @param string $this->string
     * @return string
     */
    protected function upperCamelCaseToSlug()
    {
      return $this->toSeparatedString($this->string,'-');
    }

    /**
     * my_protected_var >> myPublicVar
     * @param string $this->string
     * @return string
     */
    protected function underScoreToCamelCase()
    {
      return $this->_toCamelCase($this->string,'_',false);
    }

    /**
     * my_protected_var >> MyPublicVar
     * @param string $this->string
     * @return string
     */
    protected function underscore_to_uppercamelcase()
    {
      	return $this->_toCamelCase('_', true);
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
      return $this->_toCamelCase($this->string,'-',true);
    }

    /**
     * my-protected-var >> myPublicVar
     * @param string $this->string
     * @return string
     */
    protected function slugToCamelCase()
    {
      return $this->_toCamelCase($this->string,'-',false);
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
    private function _toCamelCase($separator = '-', $first = false)
    {
        $parts = explode($separator, $this->string);
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
      $this->string[0] = strtolower($this->string[0]);
      $func = create_function('$c', 'return "'.$separator.'" . strtolower($c[1]);');
      return preg_replace_callback('/([A-Z])/', $func, $this->string);
    }




}

