<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\NotificationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    #[Route('/notification', name: 'notification', methods: ['POST'])]
    public function notifsHtml(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        return new Response(NotificationManager::getNotificationsAsHtml($user));
    }
    
    #[Route('/notification_nb', name: 'notification_nb', methods: ['POST'])]
    public function notifsNb(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        return new Response($user->getNotifsNb());
    }
}
