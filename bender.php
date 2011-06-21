<?php

use Application\Event\CoreListener;
use Application\Bender\Bender;

define('APPLICATION_PATH', __DIR__);

require_once 'Application/Bender/Bender.php';

$bender = Bender::getInstance();

$bender->registerAutoloader()
	->loadContainer('config/Services.xml');

$bender->getConfiguration()->set('modulesPath', APPLICATION_PATH.'/Modules/');
$bender->getEventDispatcher()->addSubscriber(new CoreListener());
$bender->getCLI()->run();
