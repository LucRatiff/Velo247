<?php

namespace App\Service;

class NotificationBuilder
{
    public static function getNotification(array $rawDatas): ?Notification
    {
        $notif = NotificationElement::getNotificationElementFromRawDatas($rawDatas);
        
        $func = 'getNotif'.$notif->getType();
        
        if (!function_exists(self::$func)) {
            return null;
        }
        
        return self::$func($notif); //TODO retourner un objet notification avec un lien
    }
    
    private static function strong(string $text): string
    {
         return '<strong>'.$text.'</strong>';
    }
    
    private static function getNotifBadge(NotificationElement $notif): string
    {
        return Notification::NOTIFICATION_LIST[$notif->getType()].self::strong($notif->getBadge());
    }
    
    private static function getNotifNewResponse(NotificationElement $notif): string
    {
        return Notification::NOTIFICATION_LIST[$notif->getType()].self::strong($notif->getTopic());
    }
    
    private static function getNotifMention(NotificationElement $notif): string
    {
        return self::strong($notif->getUser()).Notification::NOTIFICATION_LIST[$notif->getType()].
                self::strong($notif->getTopic());
    }
    
    private static function getNotifLocked(NotificationElement $notif): string
    {
        return Notification::NOTIFICATION_LIST[$notif->getType()].self::strong($notif->getTopic());
    }
    
    private static function getNotifPinned(NotificationElement $notif): string
    {
        return Notification::NOTIFICATION_LIST[$notif->getType()].self::strong($notif->getTopic());
    }
}
