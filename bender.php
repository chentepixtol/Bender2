<?php

require_once 'autoload.php';

use Application\Event\CoreListener;
use Application\Bender\Bender;

$bender = Bender::getInstance();

$bender->loadContainer(realpath('.').'/config/Services.xml');

$bender->getEventDispatcher()->addSubscriber(new CoreListener());

$output = $bender->getContainer()->get('output');
$bender->getCLI()->run(null, $output);
