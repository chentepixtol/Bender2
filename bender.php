<?php

use Application\Bender\Event\Event;
use Application\Bender\ParameterEvent;
use Application\Bender\Bender;

define('APPLICATION_PATH', __DIR__);

require_once 'Application/Bender/Bender.php';

$bender = Bender::getInstance();
$bender->registerAutoloader();
$bender->getCLI()->run();
