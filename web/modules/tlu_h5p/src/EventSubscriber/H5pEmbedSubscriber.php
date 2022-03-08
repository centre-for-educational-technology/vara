<?php

namespace Drupal\tlu_h5p\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * H5P Embed event subscriber.
 */
class H5pEmbedSubscriber implements EventSubscriberInterface {

  /**
   * Kernel response event handler.
   *
   * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
   *   Response event.
   */
  public function onKernelResponse(ResponseEvent $event) {
    $request = $event->getRequest();
    $path = $request->getPathInfo();

    if (preg_match('/^\/h5p\/\d+\/embed\/?$/', $path)) {
      $response = $event->getResponse();

      if ($response->headers->has('X-Frame-Options')) {
        $response->headers->remove('X-Frame-Options');
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::RESPONSE => ['onKernelResponse', -10],
    ];
  }

}
