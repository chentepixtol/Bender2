{% include 'header.tpl' %}

{% set BaseTest = classes.get('BaseTest') %}

{{ BaseTest.printNamespace() }}

use Symfony\Component\ClassLoader\UniversalClassLoader;
require_once 'Symfony/Component/ClassLoader/UniversalClassLoader.php';

$includePath = explode(':', get_include_path());

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
	'Test' => 'Test',
	'Application' => '.',
));
$loader->register();


/**
 *
 * @author chente
 *
 */
abstract class {{ BaseTest }} extends \PHPUnit_Framework_TestCase
{

}

