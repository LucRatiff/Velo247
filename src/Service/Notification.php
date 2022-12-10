<?php

namespace App\Service;

class Notification
{
    const NOTIFICATION_LIST = [
        'Badge' => 'Vous avez obtenu un nouveau badge : ',
        'NewResponse' => 'Vous avez de nouvelles réponses à ',
        'Mention' => ' vous a mentionné à ',
        'Locked' => 'Votre sujet a été verrouillé : ',
        'Pinned' => 'Votre sujet a été épinglé : ',
    ];
    
    const BADGE_LIST = [
        'OneWeek' => 'Déjà une semaine !',
        'OneMonth' => 'Inscrit depuis un mois !',
        'OneYear' => 'Cela fait déjà un an !',
        '100Messages' => '100 messages !',
        '1000Messages' => 'Quelle piplette, 1000 messages !',
        '10000Messages' => 'Quelle assiduité ! 10 000 messages !'
    ];
    
    private string $notificationString;
    private string $route;
    
    public function __construct(string $notificationString, string $route)
    {
        $this->notificationString = $notificationString;
        $this->route = $route;
    }
    
    public function getNotificationString(): string
    {
        return $this->notificationString;
    }
    
    public function getRoute(): string
    {
        return $this->route;
    }
}
