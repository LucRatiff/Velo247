<?php

namespace App\Service;

class NotificationElement
{
    private string $type;
    private ?string $user;
    private int $date;
    private ?string $topic;
    private ?string $badge;
    private ?string $link;
    
    public function __construct(int $type, ?string $user, int $date, ?string $topic,
            ?string $badge, ?string $link)
    {
        $this->type = $type;
        $this->user = $user;
        $this->date = $date;
        $this->topic = $topic;
        $this->badge = $badge;
        $this->link = $link;
    }
    
    public static function getNotificationElementFromRawDatas(array $rawDatas): self
    {
        //faire avec ces données
        $this->type = $datas['type'];
        $this->userId = $datas['user_id'];
        $this->date = $datas['date'];
        $this->topicId = $datas['topic_id'];
        $this->badge = $datas['badge'];
        //route : liste json [route, paramètres, ...]
    }
    
    public function getType(): string
    {
        return $this->type;
    }
    
    public function getUser(): ?string
    {
        return $this->user;
    }
    
    public function getDate(): int
    {
        return $this->date;
    }
    
    public function getTopic(): ?string
    {
        return $this->topic;
    }
    
    public function getBadge(): ?string
    {
        return $this->type;
    }
    
    public function getLink(): ?string
    {
        return $this->link;
    }
}
