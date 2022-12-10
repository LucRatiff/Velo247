<?php

namespace App\Service;

/**
 * Reçoit les données du formulaire de nouveau message en réponse à un sujet
 */
class NewMessage
{
    private ?string $message = null;
    
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
