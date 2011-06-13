<?php
namespace Application\Generator\Module;

/**
 *
 *
 * @author chente
 *
 */
interface Module
{

	/**
	 *
	 * @return string
	 */
	public function getName();

	/**
	 *
	 * @return Application\Generator\File\FileCollection
	 */
	public function getFiles();

	/**
	 *
	 * @return array
	 */
	public function getTemplateDirs();

	/**
	 *
	 * @return Symfony\Component\EventDispatcher\EventSubscriberInterface
	 */
	public function getSubscriber();

	/**
	 *
	 * Initialization
	 */
	public function init();

}
