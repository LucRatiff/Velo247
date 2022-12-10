<?php

namespace App\Service;

/**
 * Reçoit les données du formulaire pour créer un nouveau sujet
 */
class NewTopic
{
    private ?string $title = null;
    private ?string $message = null;
    
    public function getTitle(): ?string
    {
        return $this->title;
    }
    
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }
    
    public function getMessage(): ?string
    {
        return $this->message;
    }
    
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }
}
