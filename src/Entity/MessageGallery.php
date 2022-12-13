<?php

namespace App\Entity;

use App\Repository\MessageGalleryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageGalleryRepository::class)]
class MessageGallery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Gallery $gallery = null;

    #[ORM\ManyToOne(inversedBy: 'messageGalleries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $date = null;

    #[ORM\Column(nullable: true)]
    private ?int $last_edited = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?int $flagged = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $reactions = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    public function setGallery(?Gallery $gallery): self
    {
        $this->gallery = $gallery;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDate(): ?int
    {
        return $this->date;
    }

    public function setDate(int $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLastEdited(): ?int
    {
        return $this->last_edited;
    }

    public function setLastEdited(?int $last_edited): self
    {
        $this->last_edited = $last_edited;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getFlagged(): ?int
    {
        return $this->flagged;
    }

    public function setFlagged(int $flagged): self
    {
        $this->flagged = $flagged;

        return $this;
    }

    public function getReactions(): ?string
    {
        return $this->reactions;
    }

    public function setReactions(?string $reactions): self
    {
        $this->reactions = $reactions;

        return $this;
    }
}
