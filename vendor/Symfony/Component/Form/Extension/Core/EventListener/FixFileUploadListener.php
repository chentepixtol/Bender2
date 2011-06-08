<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Extension\Core\EventListener;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Event\FilterDataEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\TemporaryStorage;

/**
 * Moves uploaded files to a temporary location
 *
 * @author Bernhard Schussek <bernhard.schussek@symfony-project.com>
 */
class FixFileUploadListener implements EventSubscriberInterface
{
    private $storage;

    public function __construct(TemporaryStorage $storage)
    {
        $this->storage = $storage;
    }

    public static function getSubscribedEvents()
    {
        return array(FormEvents::BIND_CLIENT_DATA => 'onBindClientData');
    }

    public function onBindClientData(FilterDataEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data) {
            $data = array();
        }

        if (!is_array($data)) {
            throw new UnexpectedTypeException($data, 'array');
        }

        $data = array_replace(array(
            'file' => '',
            'token' => '',
            'name' => '',
            'originalName' => '',
        ), $data);

        // Newly uploaded file
        if ($data['file'] instanceof UploadedFile && $data['file']->isValid()) {
            $data['token'] = (string)rand(100000, 999999);
            $directory = $this->storage->getTempDir($data['token']);
            $data['file']->move($directory);
            $data['name'] = $data['file']->getName();
            $data['originalName'] = $data['file']->getOriginalName();
        }

        // Existing uploaded file
        if (!$data['file'] && $data['token'] && $data['name']) {
            $path = $this->storage->getTempDir($data['token']) . DIRECTORY_SEPARATOR . $data['name'];

            if (file_exists($path)) {
                $data['file'] = new UploadedFile($path, $data['originalName'], null, null, null, true);
            }
        }

        // Clear other fields if we still don't have a file, but keep
        // possible existing files of the field
        if (!$data['file']) {
            $data = $form->getNormData();
        }

        $event->setData($data);
    }
}