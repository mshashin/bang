<?php
namespace App\EventListener;
use Doctrine\Common\Persistence;
use Doctrine\ORM\EntityManager;

class OnConnect
{
    public function postConnect( $event )
    {
        $conn = $event->getConnection();
        $conn->executeQuery("SET NAMES UTF8");
    }
}