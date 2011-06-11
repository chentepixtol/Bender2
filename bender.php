<?php

use Application\Bender\Bender;

define('APPLICATION_PATH', __DIR__);

require_once 'Application/Bender/Bender.php';

Bender::getInstance()->run();
