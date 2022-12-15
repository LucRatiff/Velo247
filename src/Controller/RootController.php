<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\SubCategory;
use App\Service\Constants;
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
            $subCategories = $registry->getRepository(SubCategory::class)->findAllByPositionAndCategory($c);
            
            foreach ($subCategories as $s) {
                $lastMessage = $s->getLastMessage();
                $lastAuthor = null;
                if ($lastMessage != null) {
                    $lastAuthor = $lastMessage->getUser();
                }
                $twigArray[$c->getName()][] = [
                    'id' => $s->getId(),
                    'name' => $s->getName(),
                    'description' => $s->getDescription(),
                    'topics_nb' => $s->getTopicsNb(),
                    'messages_nb' => $s->getMessagesNb(),
                    'last_message' => $lastAuthor == null ? null : [
                        'username' => $lastAuthor->getName(),
                        'photo' => $lastAuthor->getPhoto(),
                        'title' => $lastMessage->getTopic()->getTitle(),
                        'date' => (new \DateTime())->setTimestamp($lastMessage->getDate())->format(Constants::DATE_FORMAT_SLASHES_MINUTES_SENTENCE),
                        'id' => $lastMessage->getId(),
                        'topic_id' => $lastMessage->getTopic()->getId(),
                        'topic_slug' => $lastMessage->getTopic()->getSlug()
                    ]
                ];
            }
        }
        
        return $this->render('forum.html.twig', [ 'categories' => $twigArray ]);
    }
    
    #[Route('/mentions', name: 'legal')]
    public function legalNotice(): Response{
        
        return $this->render('legal_notice.html.twig', []);
    }
}
