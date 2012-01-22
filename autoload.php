<?php

require_once 'vendor/.composer/autoload.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

set_include_path(get_include_path() . PATH_SEPARATOR . 'vendor');

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
