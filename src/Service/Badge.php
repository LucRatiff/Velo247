<?php

namespace App\Service;

enum Badge: string implements EnumUtils
{
    case Messages10 = 'Déjà 10 messages !';
    case Messages100 = '100 messages !';
    case Messages1000 = 'Quelle piplette, 1000 messages !';
    case Messages10000 = 'Quelle assiduité ! 10 000 messages !';
    
    public function fromName(string $name): ?string
    {
        foreach (self::cases() as $type) {
            if ($name == $type->name) {
                return $type->value;
            }
        }
        
        return null;
    }
    
    public function getMessagesBadgeIntValueFromMessagesNb(int $messagesNb): ?int
    {
        switch ($messagesNb) {
            case 10:
                return 0;
            case 100:
                return 1;
            case 1000:
                return 2;
            case 10000:
                return 3;
            default:
                return null;
        }
    }
}
