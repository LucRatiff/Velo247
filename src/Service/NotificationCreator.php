<?php

namespace App\Service;

class NotificationCreator
{
    private int $userId;
    private NotificationRaw $notif;
    
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }
    
    public function save(NotificationCreator $creator)
    {
        
    }
    
    public function badge(int $badge): self
    {
        $this->notif = [ $userId ];
    }
}
