{% include 'header.tpl' %}

{% set BaseTest = classes.get('BaseTest') %}

{{ BaseTest.printNamespace() }}

/**
 *
 * @author chente
 *
 */
abstract class {{ BaseTest }} extends \PHPUnit_Framework_TestCase
{

}

