<?php
namespace Application\Database;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Doctrine\DBAL\DriverManager;
use Application\Config\Settings;
use Application\Event\Event;

/**
 *
 *
 * @author chente
 *
 */
class ConnectionHolder
{

    /**
     *
     *
     * @var Application\Config\Settings
     */
    private $settings;

    /**
     *
     *
     * @var Symfony\Component\EventDispatcher\EventDispatcher
     */
    private $eventDispatcher;

    /**
     *
     *
     * @var Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     *
     *
     * @param Settings $settings
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(Settings $settings, EventDispatcher $eventDispatcher){
        $this->settings = $settings;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     *
     * @return Doctrine\DBAL\Connection
     */
    public function getConnection()
    {
        if( null == $this->connection ){
            $connectionParams = $this->settings->getConnectionParams()->toArray();
            $this->connection = DriverManager::getConnection($connectionParams);
            $this->eventDispatcher->dispatch(Event::CONNECTION_ESTABILISHED, new Event(array('connection' => $this->connection)));
        }
        return $this->connection;
    }

}