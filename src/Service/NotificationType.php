<?php

namespace App\Service;

enum NotificationType implements EnumUtils
{
    case Badge = 'Vous avez obtenu un nouveau badge : ';
    case NewResponse = 'Vous avez de nouvelles réponses à ';
    case Mention = ' vous a mentionné à ';
    case Locked = 'Votre sujet a été verrouillé : ';
    case Pinned = 'Votre sujet a été épinglé : ';
    
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
