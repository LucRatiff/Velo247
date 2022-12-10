<?php

namespace App\Service;

/**
 * Reçoit les données du formulaire de modification du profil
 */
class UserEdition
{
    private ?string $name = null;
    private ?string $email = null;
    private ?string $password = null;
    private ?string $city = null;
    private ?string $country = null;
    private ?string $signature = null;
    private ?string $photo = null;
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }
    
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }
    
    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }
    
    public function getCity(): ?string
    {
        return $this->city;
    }
    
    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }
    
    public function getCountry(): ?string
    {
        return $this->country;
    }
    
    public function setCountry(?string $country): self
    {
        $this->country = $country;
        return $this;
    }
    
    public function getSignature(): ?string
    {
        return $this->signature;
    }
    
    public function setSignature(?string $signature): self
    {
        $this->signature = $signature;
        return $this;
    }
    
    public function getPhoto(): ?string
    {
        return $this->photo;
    }
    
    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;
        return $this;
    }
}
