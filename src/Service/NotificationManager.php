<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;

class NotificationManager
{
    /**
     * @param User $user
     * @return string notifs dans leur forme affichable,
     * encapsulées soit dans des a, soit dans des div
     */
    public static function getNotificationsAsHtml(User $user): string
    {
        $html = '';
        $notifs = $user->getNotifications();
        $user->purgeNotifications();
        
        if ($notifs != null && count($notifs) > 0) {
            foreach ($notifs as $n) {
                $func = 'getNotif'.$n->getType();
                
                if (function_exists(self::$func)) {
                    if ($n->getLink() != null) {
                        $html += '<a href="'.$n->getLink().'">'.self::$func($n).'</a>';
                    } else {
                        $html += '<span>'.self::$func($n).'</span>';
                    }
                }
            }
        } else {
            $html = '<div id="no-notif">Vous n\'avez pas de notification</div>';
        }
        
        return $html;
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
