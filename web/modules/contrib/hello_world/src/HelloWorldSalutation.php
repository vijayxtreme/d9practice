<?php

namespace Drupal\hello_world;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Prepares the salutation to the world.
 */

class HelloWorldSalutation {
    /** StringTranslationTrait must be used after imported with class */
    use StringTranslationTrait;

    /**
     * Returns the salutation
     */
    public function getSalutation(){
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