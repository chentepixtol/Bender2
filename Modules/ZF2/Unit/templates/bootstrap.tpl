{% include 'header.tpl' %}

use Symfony\Component\ClassLoader\UniversalClassLoader;
require_once 'Symfony/Component/ClassLoader/UniversalClassLoader.php';

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
	'Application' => '.',
));
$loader->registerNamespaceFallbacks(explode(':', get_include_path()));
$loader->register();
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_STRICT);