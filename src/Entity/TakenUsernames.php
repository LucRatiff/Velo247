<?php

namespace App\Entity;

use App\Repository\TakenUsernamesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TakenUsernamesRepository::class)]
class TakenUsernames
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $expiration_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExpirationDate(): ?int
    {
        return $this->expiration_date;
    }

    /**
     * 
     * @param int $expiration_date 1 an si pseudo inutilisé, 0 si actif
     * @return self
     */
    public function setExpirationDate(int $expiration_date): self
    {
        $this->expiration_date = $expiration_date;

        return $this;
    }
}
