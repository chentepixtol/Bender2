<?php


namespace Application\Bender;

use Symfony\Component\Config\FileLocator;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\ClassLoader\UniversalClassLoader;
use Doctrine\DBAL\DriverManager;
use Application\Bender\View;
use Application\Base\Singleton;
use Application\Config\Configuration;
use Application\Generator\Module\Module;
use Application\Database\Database;
use Application\Database\DatabaseBuilder;
use Application\CLI\CLI;
use Application\Config\Settings;
use Application\Config\Schema;
use Application\Generator\Module\ModuleCollection;
use Application\Generator\Module\Finder;
use Application\Event\Event;
use Application\Event\CoreListener;
use Application\Event\Dispatcher;


require_once 'Application/Base/Singleton.php';

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
	 * @var Application\Bender\View
	 */
	protected $view;

	/**
	 *
	 * @var Application\CLI\CLI
	 */
	protected $cli;

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
	 * Container Dependency Injection
	 * @var Symfony\Component\DependencyInjection\Container
	 */
	protected $container;

	/**
	 *
	 * run
	 */
	public function run()
	{
		$this->registerAutoloader();
		$this->loadContainer();
		$this->getConfiguration()->set('modulesPath', APPLICATION_PATH.'/Modules/');
		$this->getEventDispatcher()->addSubscriber(new CoreListener());
		$this->getCLI()->run();
	}

	/**
	 *
	 * Load Dependecy Injection Container
	 */
	public function loadContainer()
	{
		$this->container = new ContainerBuilder();
		$loader = new XmlFileLoader($this->container, new FileLocator(APPLICATION_PATH.'/config/'));
		$loader->load('Services.xml');
	}

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

		return $this;
	}

	/**
	 *
	 * @return Doctrine\DBAL\Connection
	 */
	public function getConnection()
	{
		if( null == $this->connection ){
			$connectionParams = $this->getSettings()->getConnectionParams()->toArray();
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
			$schemaManager = $this->getConnection()->getSchemaManager();
			$databaseBuilder = new DatabaseBuilder($schemaManager, $this->getSchema(), $this->getEventDispatcher());
			$this->database = $databaseBuilder->build();
		}
		return $this->database;
	}

	/**
	 * @return Application\Config\Configuration
	 */
	public function getConfiguration()
	{
		return $this->getContainer()->get('configuration');
	}

	/**
	 *
	 * @return Application\Config\Settings
	 */
	public function getSettings()
	{
		if( null == $this->settings ){
			$this->settings = $this->getContainer()->get('settings');
			$this->dispatch(Event::LOAD_SETTINGS, new Event(array('settings' => $this->settings)));
		}
		return $this->settings;
	}

	/**
	 *
	 * @return Application\Config\Schema
	 */
	public function getSchema()
	{
		if( null == $this->schema ){
			$this->schema = $this->getContainer()->get('schema');
			$this->dispatch(Event::LOAD_SCHEMA, new Event(array('schema' => $this->schema)));
		}
		return $this->schema;
	}

	/**
	 *
	 * @return Application\CLI\CLI
	 */
	public function getCLI()
	{
		if( null == $this->cli ){
			$this->cli = $this->getContainer()->get('cli');
			$this->dispatch(Event::CLI_READY, new Event(array('cli' => $this->cli)));
		}
		return $this->cli;
	}

	/**
	 *
	 * @return Symfony\Component\EventDispatcher\EventDispatcher
	 */
	public function getEventDispatcher()
	{
		return $this->getContainer()->get('eventDispatcher');
	}

	/**
	 *
	 * Symfony\Component\DependencyInjection\Container;
	 */
	public function getContainer(){
		return $this->container;
	}

	/**
	 *
	 * @param string $eventName
	 * @param Event $event
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
	 * @param Module $module
	 * @return Application\Bender\View
	 */
	public function getView(Module $module)
	{
		if( null == $this->view ){
			$this->view = $this->getContainer()->get('view');
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
	 *
	 * @return Application\Generator\Module\ModuleCollection
	 */
	public function getModules($project = '')
	{
		if( null == $this->modules ){
			$directories = $this->getConfiguration()->get('modulesPath').$project;
			$finder = new Finder($directories);
			$this->modules = $finder->findModules();
			$this->dispatch(Event::LOAD_MODULES, new Event(array('modules' => $this->modules)));
		}
		$this->modules->rewind();
		return $this->modules;
	}

}