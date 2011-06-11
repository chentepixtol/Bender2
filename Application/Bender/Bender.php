<?php

namespace Application\Bender;

use Application\Bender\Event\CoreListener;

use Symfony\Component\ClassLoader\UniversalClassLoader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Doctrine\DBAL\DriverManager;
use Application\Bender\View;
use Application\Bender\Singleton;
use Application\Bender\Event\Event;
use Application\Config\Configuration;
use Application\Generator\Module\Module;
use Application\Database\Database;
use Application\CLI\CLI;
use Application\Config\Schema;
use Application\Generator\Module\ModuleCollection;
use Application\Generator\Module\Finder;
use Application\Config\Settings;

require_once 'Application/Bender/Singleton.php';

defined('APPLICATION_PATH') or die("No se ha definido la constante APPLICATION_PATH");

/**
 *
 * @author chente
 * @method Application\Bender\Bender getInstance
 */
final class Bender extends Singleton
{

	/**
	 *
	 * @var Doctrine\DBAL\Connection
	 */
	protected $connection;

	/**
	 *
	 * @var Application\Database\Database
	 */
	protected $database;

	/**
	 *
	 *
	 * @var Application\CLI\CLI
	 */
	protected $cli;

	/**
	 *
	 * @var Application\Bender\View
	 */
	protected $view;

	/**
	 *
	 * @var Configuration
	 */
	protected $configuration;

	/**
	 *
	 * @var EventDispatcher
	 */
	protected $eventDispatcher;

	/**
	 *
	 * @var Application\Config\Schema
	 */
	protected $schema;

	/**
	 *
	 * @var Application\Generator\Module\ModuleCollection
	 */
	protected $modules;

	/**
	 *
	 * @var Application\Config\Settings
	 */
	protected $settings;

	/**
	 *
	 * @return Application\Bender\Bender
	 */
	public function registerAutoloader()
	{
		require_once APPLICATION_PATH.'/vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';

		$loader = new UniversalClassLoader();
		$loader->registerNamespaces(array(
		    'Symfony'     => APPLICATION_PATH.'/vendor/',
			'Doctrine'    => APPLICATION_PATH.'/vendor/',
			'Application' => APPLICATION_PATH,
			'Modules'     => APPLICATION_PATH,
		));

		$loader->registerPrefixes(array(
		    'Twig_'  => APPLICATION_PATH.'/vendor/',
		));

		$loader->register();

		$this->getConfiguration()->set('modulesPath', APPLICATION_PATH.'/Modules/');

		return $this;
	}

	/**
	 *
	 * @return Doctrine\DBAL\Connection
	 */
	public function getConnection(){
		if( null == $this->connection ){
			$connectionParams = $this->getSettings()->getConnectionParams();
			$this->connection = DriverManager::getConnection($connectionParams);
			$this->dispatch(Event::CONNECTION_ESTABILISHED, new Event(array('connection' => $this->connection)));
		}
		return $this->connection;
	}

	/**
	 *
	 * @return Application\Database\Database
	 */
	public function getDatabase()
	{
		if( null == $this->database ){
			$this->database = new Database();
			$this->initDatabase();
		}
		return $this->database;
	}

	/**
	 *
	 * @return Application\Config\Settings
	 */
	public function getSettings(){
		if( null == $this->settings ){
			$this->settings = new Settings();
			$this->settings->load(APPLICATION_PATH.'/config/settings.yml');
			$this->dispatch(Event::LOAD_SETTINGS, new Event(array('settings' => $this->settings)));
		}
		return $this->settings;
	}

	/**
	 *
	 * init database
	 */
	protected function initDatabase()
	{
		$this->dispatch(Event::DATABASE_BEFORE_INSPECT);
		$this->database->inspect($this->getConnection()->getSchemaManager(), $this->getSchema());
		$this->dispatch(Event::DATABASE_AFTER_INSPECT, new Event(array('database' => $this->database)));

		$this->dispatch(Event::DATABASE_BEFORE_CONFIGURE, new Event(array('database' => $this->database)));
		$this->database->configure();
		$this->dispatch(Event::DATABASE_AFTER_CONFIGURE, new Event(array('database' => $this->database)));
	}


	/**
	 *
	 * @return Application\CLI\CLI
	 */
	public function getCLI()
	{
		if( null == $this->cli ){
			$this->cli = new CLI();
			$this->cli->loadCommands();
			$this->dispatch(Event::CLI_READY, new Event(array('cli' => $this->cli)));
		}
		return $this->cli;
	}

	/**
	 *
	 * run
	 */
	public function run(){
		$this->registerAutoloader();
		$this->getEventDispatcher()->addSubscriber(new CoreListener());
		$this->getCLI()->run();
	}

	/**
	 *
	 * @param Module $module
	 * @return Application\Bender\View
	 */
	public function getView(Module $module)
	{
		if( null == $this->view ){
			$this->view = new View();
			$this->dispatch(Event::VIEW_INIT, new Event(array('view' => $this->view)));
		}

		if( $this->view->toggleToDirectory($module->getTemplateDirs()) ){
			$this->dispatch(Event::VIEW_MODULE_CREATE, new Event(
				array('view' => $this->view, 'module' => $module)
			));
		}

		return $this->view;
	}

	/**
	 * @return Application\Config\Configuration
	 */
	public function getConfiguration()
	{
		if( null == $this->configuration ){
			$this->configuration = new Configuration();
		}
		return $this->configuration;
	}

	/**
	 *
	 * @return Application\Config\Schema
	 */
	public function getSchema()
	{
		if( null == $this->schema ){
			$this->schema = new Schema();
			$this->schema->load(APPLICATION_PATH.'/config/schema.yml');
			$this->dispatch(Event::LOAD_SCHEMA, new Event(array('schema' => $this->schema)));
		}
		return $this->schema;
	}

	/**
	 *
	 * @return Symfony\Component\EventDispatcher\EventDispatcher
	 */
	public function getEventDispatcher()
	{
		if( null == $this->eventDispatcher ){
			$this->eventDispatcher = new EventDispatcher();
		}
		return $this->eventDispatcher;
	}

	/**
	 *
	 * @param string $eventName
	 * @param CoreEvent $event
	 */
	public function dispatch($eventName, Event $event = null)
	{
		if( null == $event ){
			$event = new Event();
		}
		$this->getEventDispatcher()->dispatch($eventName, $event);
	}

	/**
	 *
	 * @return Application\Generator\Module\ModuleCollection
	 */
	public function getModules($project)
	{
		if( null == $this->modules ){
			$directories = $this->getConfiguration()->get('modulesPath') .$project;
			$finder = new Finder($directories);
			$this->modules = $finder->getModules();
			$this->dispatch(Event::LOAD_MODULES, new Event(array('modules' => $this->modules)));
		}
		return $this->modules;
	}

}