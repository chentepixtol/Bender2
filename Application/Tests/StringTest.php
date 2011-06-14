<?php

require_once 'Application/Tests/BaseTest.php';

class StringTest extends BaseTest
{

    const CAMELCASE = 'formattedStringForTest';
    const UPPERCAMELCASE = 'FormattedStringForTest';
    const UPPERCASE = 'FORMATTED_STRING_FOR_TEST';
    const UNDERSCORE = 'formatted_string_for_test';
    const SLUG = 'formatted-string-for-test';

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }



    /**
     *
     */
    public function testSlugToUnderScore()
    {
        $this->assertEquals(1 , 1);
    }
}


