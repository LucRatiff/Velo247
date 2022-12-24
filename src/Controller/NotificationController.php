<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\NotificationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    #[Route('/notification', name: 'notification', methods: ['PUT'])]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        return new Response(NotificationManager::getNotificationsAsStrings($user));
    }
}
