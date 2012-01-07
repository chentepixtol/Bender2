<?php

namespace Test;

require_once 'Test/BaseTest.php';

use Application\Native\String;

class StringTest extends BaseTest
{

    /**
     *
     * fixtures
     */
    const CAMELCASE = 'formattedStringForTest';
    const UPPERCAMELCASE = 'FormattedStringForTest';
    const UPPERCASE = 'FORMATTED_STRING_FOR_TEST';
    const UNDERSCORE = 'formatted_string_for_test';
    const SLUG = 'formatted-string-for-test';


    /**
     * @dataProvider getTypes
     * @test
     */
    public function conversion($str, $type)
    {
        $string = new String($str, $type);
        $this->validateString($string);
        $this->assertEquals($string->__toString(), $str);
        $this->assertTrue($string->equal($str));
    }

    /**
     * @dataProvider getTypes
     * @test
     * @expectedException InvalidArgumentException
     */
    public function unknown($str, $type)
    {
        $string = new String($str, $type);
        $string->toUnknown();
    }

    /**
     *
     * @param Application\Native\String $string
     */
    protected function validateString( $string)
    {
        $this->assertEquals(self::CAMELCASE, $string->toCamelCase());
        $this->assertEquals(self::UPPERCAMELCASE, $string->toUpperCamelCase());
        $this->assertEquals(self::UPPERCASE, $string->toUpperCase() );
        $this->assertEquals(self::UNDERSCORE, $string->toUnderscore());
        $this->assertEquals(self::SLUG, $string->toSlug() );
    }

    /**
     *
     * @return array
     */
    public function getTypes()
    {
        return array(
            array(self::CAMELCASE, String::CAMELCASE),
            array(self::UPPERCAMELCASE, String::UPPERCAMELCASE),
            array(self::UPPERCASE, String::UPPERCASE),
            array(self::UNDERSCORE, String::UNDERSCORE),
            array(self::SLUG, String::SLUG),
        );
    }

}


