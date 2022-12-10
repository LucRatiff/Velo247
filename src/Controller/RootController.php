<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RootController extends AbstractController
{
    #[Route('/', name: 'root')]
    public function displayRoot(ManagerRegistry $registry): Response
    {
        $twigArray = array();
        $categories = $registry->getRepository(Category::class)->findAllByPosition();
        
        foreach ($categories as $c) {
            $subCategories = $registry->getRepository(Category::class)->findAllByPositionAndCategory($c);
            
            foreach ($subCategories as $s) {
                $lastMessage = $s->getLastMessage();
                $lastAuthor = $lastMessage->getUser();
                $twigArray[$c->getName()][] = [
                    'id' => $s->getId(),
                    'name' => $s->getName(),
                    'description' => $s->getDescription(),
                    'topics_nb' => $s->getTopicsNb(),
                    'messages_nb' => $s->getMessagesNb(),
                    'last_message' => [
                        'username' => $lastAuthor->getName(),
                        'photo' => $lastAuthor->getPhoto(),
                        'title' => $lastMessage->getTitle(),
                        'date' => $lastMessage->getDate()
                    ]
                ];
            }
        }
        
        return $this->render('forum.html.twig', [ 'categories' => $twigArray ]);
    }
}
