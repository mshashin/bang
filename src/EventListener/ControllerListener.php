<?php

namespace App\EventListener;

use App\Controller\InitializableController;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class ControllerListener
{
    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) return;

        $controller = $controller[0];

        if ($controller instanceof InitializableController) $controller->initialize($event->getRequest());
    }
}
