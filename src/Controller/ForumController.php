<?php

namespace App\Controller; //TODO nouveaux messages


use App\Entity\MessageTopic;
use App\Entity\Notification;
use App\Entity\SubCategory;
use App\Entity\Topic;
use App\Entity\User;
use App\Form\Type\NewMessageType;
use App\Form\Type\NewTopicType;
use App\Service\Badge;
use App\Service\Constants;
use App\Service\NewMessage;
use App\Service\NewTopic;
use App\Service\NotificationManager;
use App\Service\NotificationType;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    #[Route('/forum', name: 'forum_redirect')]
    #[Route('/forum/category', name: 'category_redirect')]
    #[Route('/forum/topic', name: 'topic_redirect')]
    public function redirectToRoot(): Response
    {
        return $this->redirectToRoute('root');
    }
    
    #[Route('/forum/category/{sub_category_id}', name: 'sub_category')]
    public function subCategory(ManagerRegistry $registry, int $sub_category_id): Response
    {
        $subCategory = $registry->getRepository(SubCategory::class)->find($sub_category_id);
        
        if (!$subCategory) {
            throw $this->createNotFoundException('Cette catégorie n\'existe pas ou a été supprimée');
        }
        
        $topics = $subCategory->getTopics();
        $topicsArray = $topics->toArray();
        $finalTopics = array();
        $removals = array();
        $twigArray = array();
        
        if ($topics->count() > 0) {
            foreach ($topicsArray as $t) {
                if ($t->isPinned()) {
                    $finalTopics[] = $t;
                    $removals[] = $i;
                }
            }
            
            $i = 0;
            foreach ($topicsArray as $t) {
                if (!in_array($i++, $removals)) {
                    $finalTopics[] = $t;
                }
            }

            foreach ($finalTopics as $t) {
                $user = $t->getUser();
                $lastMessage = $t->getLastMessage();
                $lastUser = $lastMessage->getUser();
                $usersViews = $t->getNewMessagesViewsUsers();
                $newMessages = true;

                if ($this->isGranted("ROLE_USER")) {
                    /** @var User $user */
                    $user = $this->getUser();
                    $id = $user->getId();

                    foreach ($usersViews as $u) {
                        if ($id == $u->getId()) {
                            $newMessages = false;
                            break;
                        }
                    }
                }

                $twigArray[] = [
                    'id' => $t->getId(),
                    'title' => $t->getTitle(),
                    'slug' => $t->getSlug(),
                    'locked' => $t->isLocked(),
                    'pinned' => $t->isPinned(),
                    'author' => $user->getName(),
                    'author_photo' => $user->getPhoto(),
                    'date' => (new DateTime())->setTimestamp($t->getDate())->format(Constants::DATE_FORMAT_SLASHES_MINUTES_SENTENCE),
                    'new_messages' => $newMessages,
                    'views_nb' => $t->getViewsNb(),
                    'messages_nb' => $t->getMessagesNb(),
                    'last_message' => [
                        'id' => $lastMessage->getId(),
                        'author' => $lastUser->getName(),
                        'photo' => $lastUser->getPhoto(),
                        'date' => (new DateTime())->setTimestamp($lastMessage->getDate())->format(Constants::DATE_FORMAT_SLASHES_MINUTES),
                    ]
                ];
            }
        }
        
        return $this->render('forum_sub_category.html.twig', [
            'name' => $subCategory->getName(),
            'sub_category_id' => $subCategory->getId(),
            'topics' => $twigArray,
        ]);
    }
    
    #[Route('/forum/topic/{topic_id}/{topic_slug}', name: 'topic')]
    public function topic(Request $request, ManagerRegistry $registry, int $topic_id,
            string $topic_slug): Response
    {
        $topic = $registry->getRepository(Topic::class)->find($topic_id);
        $subCategory = $topic->getSubCategory();
        
        if (!$topic) {
            throw $this->createNotFoundException('Ce sujet n\'existe pas ou a été supprimé');
        }
        
        $topicArray = [
            'id' => $topic->getId(),
            'title' => $topic->getTitle(),
            'locked' => $topic->isLocked(),
            'pinned' => $topic->isPinned(),
            'category' => $subCategory->getName(),
            'sub_category' => $subCategory->getName(),
            'sub_category_id' => $subCategory->getId()
        ];
        
        $messagesArray = array();
        $usersArray = array();
        $messages = $topic->getMessages();
        
        foreach ($messages as $i => $m) {
            $author = $m->getUser();
            $authorId = $author->getId();
            if (!isset($usersArray[$authorId])) {
                $roles = $author->getRoles();
                $role = in_array('ROLE_ADMIN', $roles) ? 'Administrateur'
                        : ((in_array('ROLE_MOD', $roles) ? 'Modérateur' : null));
                $usersArray[$authorId] = [
                    'name' => $author->getName(),
                    'photo' => $author->getPhoto(),
                    'role' => $role,
                    'messages_nb' => $author->getMessagesNb(),
                    'city' => $author->getCity(),
                    'country' => $author->getCountry(),
                    'signature' => $author->getSignature()
                ];
            }
            $messagesArray[] = [
                'id' => $m->getId(),
                'author_id' => $authorId,
                'content' => $m->getContent(),
                'date' => (new DateTime())->setTimestamp($m->getDate())->format(Constants::DATE_FORMAT_SLASHES_MINUTES_SENTENCE),
                'last_edited' => $m->getLastEdited() == null ? null : (new DateTime())->setTimestamp($m->getLastEdited())
                    ->format(Constants::DATE_FORMAT_SLASHES_MINUTES_SENTENCE)
            ];
        }
        
        $answer = ($this->isGranted("ROLE_MOD") || $this->isGranted("ROLE_ADMIN"))
                || ($this->isGranted('ROLE_USER') && !$topic->isLocked());
        
        $twigArray = [
            'topic' => $topicArray,
            'messages' => $messagesArray,
            'authors' => $usersArray,
            'answer' => $answer
        ];
        
        if ($answer) {
            $newMessage = new NewMessage();
            $form = $this->createForm(NewMessageType::class, $newMessage);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var User $user */
                $user = $this->getUser();
                $newMessage = $form->getData();

                $message = (new MessageTopic())->hydrate((new DateTime('now'))->getTimestamp(),
                        $user, $newMessage->getMessage(), $topic);
                $topic->addMessage($message);
                $user->incrementMessagesNb();
                $subCategory->incrementMessagesNb();

                $manager = $registry->getManager();
                $manager->persist($topic);
                $manager->persist($message);
                $manager->persist($user);
                $manager->persist($subCategory);
                $manager->flush();

                return $this->redirectToRoute('topic', ['topic_id' => $topic_id, 'topic_slug' => $topic_slug]);
            }
            
            $twigArray['form'] = $form;
            
            return $this->renderForm('topic.html.twig', $twigArray);
        }
        
        return $this->render('topic.html.twig', $twigArray);
    }
    
    #[Route('/forum/new/{sub_category_id}', name: 'new_topic')]
    public function newTopic(Request $request, ManagerRegistry $registry, int $sub_category_id): Response
    {
        $subCategory = $registry->getRepository(SubCategory::class)->find($sub_category_id);
        $category = $subCategory->getCategory();
        
        $newTopic = new NewTopic();
        $form = $this->createForm(NewTopicType::class, $newTopic);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            
            $newTopic = $form->getData();
            $title = $newTopic->getTitle();
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
            $date = (new DateTime("now"))->getTimestamp();
            
            $message = (new MessageTopic())->hydrate($date, $user, $newTopic->getMessage());
            $topic = (new Topic())->hydrateFirst($date, $title, $slug, $subCategory, $user, $message);
            $message->setTopic($topic);
            $subCategory->incrementTopicsNb();
            $subCategory->setLastMessage($message);
            $user->incrementMessagesNb();
            
            $messagesNb = $user->getMessagesNb();
            if (NotificationManager::isAchieved(null, $messagesNb)) {
                $notif = (new Notification())->setType(NotificationType::Badge->name)
                        ->setBadge(Badge::getMessagesBadgeIntValueFromMessagesNb($messagesNb))
                        ->setDate((new \DateTime('now'))->getTimestamp());
            }
            
            $manager = $registry->getManager();
            $manager->persist($message);
            $manager->persist($topic);
            $manager->persist($subCategory);
            $manager->persist($user);
            $manager->flush();
            
            return $this->redirectToRoute('topic', ['topic_id' => $topic->getId(), 'topic_slug' => $slug]);
        }
        
        return $this->renderForm('new_topic.html.twig', [
            'category' => $category->getName(),
            'sub_category' => $subCategory->getName(),
            'form' => $form,
        ]);
    }
}
