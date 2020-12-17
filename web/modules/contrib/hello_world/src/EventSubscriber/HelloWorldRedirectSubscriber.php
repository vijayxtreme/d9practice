<?php

namespace Drupal\hello_world\EventSubscriber;

use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Url;
use Drupal\Core\Routing\LocalRedirectResponse;
/**
 * Subscribes to the Kernel Request event and redirects to the homepage
 * when the user has the "non_grata" role
 */
class HelloWorldRedirectSubscriber implements EventSubscriberInterface {
    
    /**
     * @var \Drupal\Core\Session\AccountProxyInterface
     */
    protected $currentUser;

    /**
     * @var \Drupal\Core\Routing\CurrentRouteMatch
     */
    protected $currentRouteMatch;

    /**
     * HelloWorldRedirectSubscriber constructor
     * 
     * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
     * @param \Drupal\Core\Routing\CurrentRouteMatch $currentRouteMatch
     */
    public function __construct(AccountProxyInterface $currentUser, CurrentRouteMatch $currentRouteMatch) {
        $this->currentUser = $currentUser;
        $this->currentRouteMatch = $currentRouteMatch;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {        
        /** onRequest is a callback function to our getSubscribedEvents for kernel.request 
         * the second number is the priority, with 0 being the highest priority
        */

        $events[KernelEvents::REQUEST][] = ['onRequest', 0];
        return $events;
    }

    /**
     * Handler for the kernel request event.
     * 
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onRequest(GetResponseEvent $event){
        $route_name = $this->currentRouteMatch->getRouteName();
       
        if ($route_name !== 'hello_world.hello'){
            return ;
        }

        $roles = $this->currentUser->getRoles();
        /**
         * if this user is a non-grata user, redirect them instead of letting them
         * see the hello world page
         */
        if (in_array('non_grata', $roles)){
            $url = Url::fromUri('internal:/');
            $event->setResponse(new LocalRedirectResponse($url->toString()));
        }
        /**
         * Use setResponse to finish the Request and give a response otherwise this event
         * propagates to next class
         */
    }



}