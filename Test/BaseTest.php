<?php
namespace Test;

use Application\Bender\Bender;

require_once 'autoload.php';

/**
 *
 * @author chente
 *
 */
abstract class BaseTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @return Application\Bender\Bender
     */
    public function getBender()
    {
        return Bender::getInstance();
    }

}

