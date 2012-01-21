<?php

require_once 'vendor/.composer/autoload.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Application' => '.',
    'Test'        => '.',
    'Modules'     => '.',
));

$loader->registerPrefixes(array(
    'Zend_'  => 'vendor/',
));

$loader->register();