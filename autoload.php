<?php

require_once 'vendor/symfony/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
		'Symfony'          => 'vendor/symfony/symfony/src',
		'Doctrine\\Common' => 'vendor/doctrine/common/lib',
		'Doctrine\\DBAL'   => 'vendor/doctrine/dbal/lib',
		'Application'      => '.',
		'Test'            => '.',
		'Modules'          => '.',
));

$loader->registerPrefixes(array(
		'Twig_'  => 'vendor/twig/twig/lib',
		'Zend_'  => 'vendor/',
));

$loader->register();