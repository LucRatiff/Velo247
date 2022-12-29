<?php

namespace App\Controller;

use App\Entity\MessageTopic;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageActionsController extends AbstractController
{
    #[Route('/api/message/modify/{id}', name: 'message_modify', methods: ['POST'])]
    public function modify(Request $request, ManagerRegistry $registry, int $id): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        $message = $registry->getRepository(MessageTopic::class)->find($id);
        
        if ($message && ($this->isGranted("ROLE_MOD") || $message->getUser()->getId() == $user->getId())) {
            $message->setContent($request->request->get('content'));
            $manager = $registry->getManager();
            $manager->persist($message);
            $manager->flush();
            
            return new Response('true');
        }
        
        return new Response('false');
    }
    
    #[Route('/api/message/delete/{id}', name: 'message_delete', methods: ['POST'])]
    public function delete(ManagerRegistry $registry, int $id): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        $message = $registry->getRepository(MessageTopic::class)->find($id);
        
        if ($message && ($this->isGranted("ROLE_MOD") || $message->getUser()->getId() == $user->getId())) {
            $user = $message->getUser();
            $user->setMessagesNb($user->getMessagesNb() - 1);
            $topic = $message->getTopic();
            $topic->removeMessage($message);
            $topic->setMessagesNb($topic->getMessagesNb() - 1);
            $subCategory = $topic->getSubCategory();
            $subCategory->setMessagesNb($subCategory->getMessagesNb() - 1);
            if ($topic->getLastMessage()->getId() == $message->getId()) {
                $messages = $topic->getMessages()->toArray();
                $topic->setLastMessage($messages[count($messages) - 1]);
                if ($subCategory->getLastMessage()->getId() == $message->getId()) {
                    $topics = $subCategory->getTopics();
                    $lastDate = 0;
                    $lastMessage = null;
                    
                    foreach ($topics as $t) {
                        $lastM = $t->getLastMessage();
                        $date = $lastM->getDate();
                        if ($date > $lastDate) {
                            $lastDate = $date;
                            $lastMessage = $lastM;
                        }
                    }
                    
                    $subCategory->setLastMessage($lastMessage);
                }
            }
            $manager = $registry->getManager();
            $manager->persist($user);
            $manager->persist($topic);
            $manager->persist($subCategory);
            $manager->flush();
            
            return new Response('true');
        }
        
        return new Response('false');
    }
    
    #[Route('/api/message/delete-topic/{message_id}', name: 'topic_delete', methods: ['POST'])]
    public function deleteTopic(ManagerRegistry $registry, int $message_id): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        $message = $registry->getRepository(MessageTopic::class)->find($message_id);
        
        if ($message && ($this->isGranted("ROLE_MOD") || $message->getUser()->getId() == $user->getId())) {
            $topic = $message->getTopic();
            $messages = $topic->getMessages();
            $messagesNb = count($messages);
            $manager = $registry->getManager();
            $users = array();
            $subCategory = $topic->getSubCategory();
            $subCategory->removeTopic($topic);
            $replaceLastInSubCategory = $subCategory->getLastMessage()->getId() == end($messages)->getId();
            $subCategory->setTopicsNb($subCategory->getTopicsNb() - 1);
            $subCategory->setMessagesNb($subCategory->getMessagesNb() - $messagesNb);
            
            foreach ($messages as $m) {
                $user = $m->getUser();
                if (!isset($users[$user])) {
                    $users[$user] = 1;
                } else {
                    $users[$user]++;
                }
                $topic->removeMessage($m);
            }
            
            if ($replaceLastInSubCategory) {
                $topics = $subCategory->getTopics();
                $lastDate = 0;
                $lastMessage = null;
                    
                foreach ($topics as $t) {
                    $lastM = $t->getLastMessage();
                    $date = $lastM->getDate();
                    if ($date > $lastDate) {
                        $lastDate = $date;
                        $lastMessage = $lastM;
                    }
                }

                $subCategory->setLastMessage($lastMessage);
            }
            
            foreach ($users as $u => $nb) {
                $u->setMessagesNb($u->getMessagesNb() - $nb);
                $manager->persist($u);
            }
            
            $manager->persist($subCategory);
            $manager->flush();
            
            return new Response('true');
        }
        
        return new Response('false');
    }
}
