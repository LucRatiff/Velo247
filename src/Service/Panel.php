<?php

namespace App\Service;

use App\Entity\MessageTopic;
use Doctrine\Persistence\ManagerRegistry;

class Panel
{
    private const MESSAGES_NB_TO_DISPLAY = 10;
    
    private ManagerRegistry $registry;
    
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }
    
    /**
     * 
     * @return un tableau associatif avec les clés :
     * - users_nb : le nombre total d'utilisateurs
     * - topics_nb : le nombre total de sujets
     * - messages_nb : le nombre total de messages dans le forum
     * - last_username : pseudo du dernier inscrit
     */
    public function getStats(): array
    {
        $result = array();
        $queries = [
            'users_nb' => "SELECT COUNT(*) AS r FROM user",
            'topics_nb' => "SELECT COUNT(*) AS r FROM topic",
            'messages_nb' => "SELECT COUNT(*) AS r FROM message_topic",
            'last_username' => "SELECT name AS r FROM user ORDER BY id DESC LIMIT 0,1"
        ];
        $default = "--";
        
        foreach ($queries as $key => $query) {
            $co = $this->registry->getConnection();
            $datas = $co
                    ->prepare($query)
                    ->executeQuery()
                    ->fetchAllAssociative()
                    ;
            
            if (count($datas) > 0) {
                $result[$key] = $datas[0]['r'];
            } else {
                $result[$key] = $default;
            }
        }
        
        return $result;
    }
    
    /**
     * @return un tableau de tableaux associatifs avec les clés :
     * - title : titre du sujet
     * - sub_category : nom de la sous-catégorie
     * - author : username
     * - date
     * - content : contenu tronqué du message
     * - link : lien du message (id-sous-catégorie/id-topic/slug-topic/?msg=id-message
     */
    public function getLastMessages(): array
    {
        $array = array();
        $lastMessages = $this->registry->getRepository(MessageTopic::class)
                ->getLastMessages(self::MESSAGES_NB_TO_DISPLAY);
        
        foreach ($lastMessages as $m) {
            $topic = $m->getTopic();
            $subCategory = $topic->getSubCategory();
            $array[] = [
                'id' => $m->getId(),
                'topic_id' => $topic->getId(),
                'topic_slug' => $topic->getSlug(),
                'title' => $topic->getTitle(),
                'sub_category' => $subCategory->getName(),
                'author' => $m->getUser()->getName(),
                'date' => (new \DateTime())->setTimestamp($m->getDate())->format(Constants::DATE_FORMAT_SLASHES_MINUTES_SENTENCE),
                'content' => $m->getContent(),
                'link' => 'topic/'.$topic->getId().'/'
                    .$topic->getSlug().'/?msg='.$m->getId()
            ];
        }
        
        return $array;
    }
}
