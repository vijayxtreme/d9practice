<?php

namespace Drupal\hello_world;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Prepares the salutation to the world.
 */

class HelloWorldSalutation {
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var \Drupal\Core\Config\ConfigFactoryInterface
     */
    protected $configFactory;

    /**
     * HelloWorldSalutation constructor
     * 
     * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(ConfigFactoryInterface $config_factory, EventDispatcherInterface $eventDispatcher){
        $this->configFactory = $config_factory;
        $this->eventDispatcher = $eventDispatcher;
    }


    /** StringTranslationTrait must be used after imported with class */
    use StringTranslationTrait;

    /**
     * Returns the salutation
     */
    public function getSalutation(){
        $config = $this->configFactory->get('hello_world.custom_salutation');
        $salutation = $config->get('salutation');

        if($salutation != ""){
            $event = new SalutationEvent();
            $event->setValue($salutation);
            $event = $this->eventDispatcher->dispatch(SalutationEvent::EVENT, $event);
            return $event->getValue();
        }

        /** \DateTime() is a native function */
        $time = new \DateTime();
        if ((int) $time->format('G') >= 00 && (int) $time->format('G') < 12){
            return $this->t('Good morning world');
        }
        if ((int) $time->format('G') >= 12 && (int) $time->format('G') < 18){
            return $this->t('Good afternoon world');
        }
        if ((int) $time->format('G') >= 18){
            return $this->t('Good evening world');
        }
    }
}