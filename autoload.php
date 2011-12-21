<?php

require_once 'vendor/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
		'Symfony\\Component\\Console'             => 'vendor/symfony/console',
        'Symfony\\Component\\ClassLoader'         => 'vendor/symfony/class-loader',
        'Symfony\\Component\\Config'              => 'vendor/symfony/config',
        'Symfony\\Component\\DependencyInjection' => 'vendor/symfony/dependency-injection',
        'Symfony\\Component\\EventDispatcher'     => 'vendor/symfony/event-dispatcher',
        'Symfony\\Component\\Finder'              => 'vendor/symfony/finder',
        'Symfony\\Component\\Yaml'                => 'vendor/symfony/yaml',
		'Doctrine\\Common'             => 'vendor/doctrine/common/lib',
		'Doctrine\\DBAL'               => 'vendor/doctrine/dbal/lib',
		'Application' => '.',
		'Test'        => '.',
		'Modules'     => '.',
));

$loader->registerPrefixes(array(
		'Twig_'  => 'vendor/twig/twig/lib',
		'Zend_'  => 'vendor/',
));

$loader->register();