<?php



namespace Application\Bender;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\ClassLoader\UniversalClassLoader;
use Application\Bender\View;
use Application\Base\Singleton;
use Application\Config\Configuration;
use Application\Generator\Module\Module;
use Application\Generator\File\Routes;
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
	 * @var Application\Bender\View
	 */
	protected $view;

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
	 * Load Dependecy Injection Container
	 * @return Application\Bender\Bender
	 */
	public function loadContainer($services)
	{
		$this->container = new ContainerBuilder();
		$loader = new XmlFileLoader($this->container, new FileLocator(APPLICATION_PATH));
		$loader->load($services);

		return $this;
	}

	/**
	 *
	 * @return Application\Bender\Bender
	 */
	public function registerAutoloader()
	{
		require_once APPLICATION_PATH.'/vendor/symfony/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

		$loader = new UniversalClassLoader();
		$loader->registerNamespaces(array(
		    'Symfony'          => APPLICATION_PATH.'/vendor/symfony/symfony/src',
			'Doctrine\\Common' => APPLICATION_PATH.'/vendor/doctrine/common/lib',
			'Doctrine\\DBAL'   => APPLICATION_PATH.'/vendor/doctrine/dbal/lib',
			'Application'      => APPLICATION_PATH,
			'Tests'            => APPLICATION_PATH,
			'Modules'          => APPLICATION_PATH,
		));

		$loader->registerPrefixes(array(
		    'Twig_'  => APPLICATION_PATH.'/vendor/twig/twig/lib',
			'Zend_'  => APPLICATION_PATH.'/vendor/',
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
		return $this->getContainer()->get('connectionHolder')->getConnection();
	}

	/**
	 *
	 * @return Application\Database\Database
	 */
	public function getDatabase()
	{
		return $this->getContainer()->get('databaseBuilder')->build();
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
			$this->getEventDispatcher()->dispatch(Event::LOAD_SETTINGS, new Event(array('settings' => $this->settings)));
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
			$this->getEventDispatcher()->dispatch(Event::LOAD_SCHEMA, new Event(array('schema' => $this->schema)));
		}
		return $this->schema;
	}

	/**
	 *
	 * @return Application\CLI\CLI
	 */
	public function getCLI()
	{
		return $this->getContainer()->get('cli');
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
	 * @return Application\Generator\Classes
	 */
	public function getClasses(){
		return $this->getContainer()->get('classes');
	}

	/**
	 *
	 * @param Module $module
	 * @return Application\Bender\View
	 */
	public function getView(Module $module)
	{
		$view = $this->getContainer()->get('view');
		$view->toggleToModule($module);

		return $view;
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
			$this->getEventDispatcher()->dispatch(Event::LOAD_MODULES, new Event(array('modules' => $this->modules)));
		}
		$this->modules->rewind();
		return $this->modules;
	}

}