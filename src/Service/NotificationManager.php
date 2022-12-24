<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;

class NotificationManager
{
    const NOTIFICATION_LIST = [
        'Badge' => 'Vous avez obtenu un nouveau badge : ',
        'NewResponse' => 'Vous avez de nouvelles réponses à ',
        'Mention' => ' vous a mentionné à ',
        'Locked' => 'Votre sujet a été verrouillé : ',
        'Pinned' => 'Votre sujet a été épinglé : ',
    ];
    
    const BADGE_LIST = [
        '100Messages' => '100 messages !',
        '1000Messages' => 'Quelle piplette, 1000 messages !',
        '10000Messages' => 'Quelle assiduité ! 10 000 messages !'
    ];
    
    /**
     * @param User $user
     * @return array|null tableau de notifs dans leur forme affichable,
     * encapsulées soit dans des a, soit dans des div
     */
    public static function getNotificationsAsStrings(User $user): ?array
    {
        $notifs = $user->getNotifications();
        
        if ($notifs != null) {
            $array = array();
            
            foreach ($notifs as $n) {
                $func = 'getNotif'.$n->getType();
                
                if (function_exists(self::$func)) {
                    if ($n->getLink() != null) {
                        $array[] = '<a href="'.$n->getLink().'">'.self::$func($n).'</a>';
                    } else {
                        $array[] = '<div>'.self::$func($n).'</div>';
                    }
                }
            }
            
            return count($array) > 0 ? $array : null;
        }
        
        return null;
    }
    
    private static function strong(string $text): string
    {
         return '<strong>'.$text.'</strong>';
    }
    
    private static function getNotifBadge(Notification $notif): string
    {
        return self::NOTIFICATION_LIST[$notif->getType()].self::strong($notif->getBadge());
    }
    
    private static function getNotifNewResponse(Notification $notif): string
    {
        return self::NOTIFICATION_LIST[$notif->getType()].self::strong($notif->getTopic());
    }
    
    private static function getNotifMention(Notification $notif): string
    {
        return self::strong($notif->getUser()).self::NOTIFICATION_LIST[$notif->getType()].
                self::strong($notif->getTopic());
    }
    
    private static function getNotifLocked(Notification $notif): string
    {
        return self::NOTIFICATION_LIST[$notif->getType()].self::strong($notif->getTopic());
    }
    
    private static function getNotifPinned(Notification $notif): string
    {
        return self::NOTIFICATION_LIST[$notif->getType()].self::strong($notif->getTopic());
    }
}
