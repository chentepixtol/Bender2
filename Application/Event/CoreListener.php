<?php

namespace Application\Event;

use Application\File\Delete;

use Application\Generator\File\Writer;

use Application\File\Copy;

use Application\Config\Configuration;

use Application\Generator\BaseClass;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Application\Bender\Bender;

/**
 *
 * @author chente
 *
 */
class CoreListener implements EventSubscriberInterface
{

    /**
     *
     * @return array
     */
    public static function getSubscribedEvents(){
        return array(
            Event::SAVE_FILE => 'onSaveFile',
            Event::SAVE_FILES => 'onSaveFiles',
            Event::VIEW_INIT => 'onViewInit',
            Event::LOAD_SETTINGS => 'onLoadSettings',
            Event::GENERATOR_FINISH => 'copyFiles',
            Event::GENERATOR_START => 'clearOutputDir',
        );
    }

    /**
     *
     */
    public function copyFiles()
    {
        $settings = $this->getBender()->getSettings();
        $options = $settings->getOptions();

        if( $options->has('copy') ){

            $copyOptions = $options->get('copy');
            $directories = $copyOptions->get('directories', new Configuration())->toArray();

            $encoding = $settings->getEnconding();
            $overwrite = $copyOptions->get('overwrite', false);
            $copy = new Copy(new Writer($encoding, $encoding, $overwrite));
            $copy->setEventDispatcher($this->getBender()->getEventDispatcher());

            $this->getOutput()->writeln("");

            if( $copyOptions->has('compare_app') ){
                $app = $copyOptions->get('compare_app');
                $this->getBender()->getEventDispatcher()->addListener('copy.skip_file', function(Event $event) use($app){
                    exec("{$app} {$event->get('source')} {$event->get('destination')}");
                });
            }

            foreach( $directories as $from => $to ){

                $project = $this->getBender()->getConfiguration()->get('project');
                $in = $settings->getOutputDir() . DIRECTORY_SEPARATOR . $project;
                $copy->addPath($in . DIRECTORY_SEPARATOR . $from, $to);

                $this->getOutput()->writeln("<info>Directorio {$from} copiado a {$to}</info>");
            }

            $this->getOutput()->writeln("");
            $copy->exec();
        }
    }

    /**
     *
     */
    public function clearOutputDir()
    {
        $delete = new Delete();
        $delete->addPath($this->getBender()->getSettings()->getOutputDir());
        $delete->exec();
    }

    /**
     *
     * @param Event $event
     */
    public function onViewInit(Event $event){
        $view = $event->get('view');
        $view->addGlobal('classes', $this->getBender()->getClasses());
    }

    /**
     *
     * @param Event $event
     */
    public function onLoadSettings(Event $event){
        //TODO Cambiar esto al settings
        BaseClass::addIncludes(false);
    }

    /**
     *
     *
     * @param Event $event
     */
    public function onSaveFile(Event $event){
        static $countFiles = 0; $countFiles++;
        $this->getOutput()->writeln(sprintf("<info>  {$countFiles}. %s</info>" , $event->get('filename')));
    }

    /**
     *
     *
     * @param Event $event
     */
    public function onSaveFiles(Event $event){
        $this->getOutput()->writeln(sprintf("<info>%s</info>" , $event->get('module')->getName()));
    }

    /**
     *
     * @return Application\Bender\Bender
     */
    public function getBender(){
        return Bender::getInstance();
    }

    /**
     *
     * @return Symfony\Component\Console\Output\ConsoleOutput
     */
    public function getOutput(){
        return $this->getBender()->getContainer()->get('output');
    }

}