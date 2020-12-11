<?php

namespace Drupal\hello_world;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Prepares the salutation to the world.
 */

class HelloWorldSalutation {
    /**
     * @var \Drupal\Core\Config\ConfigFactoryInterface
     */
    protected $configFactory;

    /**
     * HelloWorldSalutation constructor
     * 
     * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
     */
    public function __construct(ConfigFactoryInterface $config_factory){
        $this->configFactory = $config_factory;
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
            return $salutation;
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