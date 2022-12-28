<?php

namespace App\Service;

enum NotificationType: string implements EnumUtils
{
    case Badge = 'Vous avez obtenu un nouveau badge : ';
    case NewResponse = 'Vous avez de nouvelles réponses à ';
    case Mention = ' vous a mentionné à ';
    case Locked = 'Votre sujet a été verrouillé : ';
    case Pinned = 'Votre sujet a été épinglé : ';
    case TopicRemoved = 'Votre sujet a été supprimé';
    case MessageRemoved = 'Votre message a été supprimé : ';
    case GalleryRemoved = 'Votre galerie a été supprimée : ';
    case GalleryPhotoRemoved = 'Une photo a été supprimée dans votre galerie : ';
    case GalleryMessageRemoved = 'Votre commentaire dans une gallerie a été supprimé : ';
    
    public function fromName(string $name): ?string
    {
        foreach (self::cases() as $type) {
            if ($name == $type->name) {
                return $type->value;
            }
        }
        
        return null;
    }
}
