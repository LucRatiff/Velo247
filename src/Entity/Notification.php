<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $date = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Topic $topic = null;

    #[ORM\Column(nullable: true)]
    private ?int $badge = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    private ?User $other_user = null;

    public function __construct()
    {
        $this->user_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getBadge(): ?int
    {
        return $this->badge;
    }

    public function setBadge(?int $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

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

    public function getOtherUser(): ?User
    {
        return $this->other_user;
    }

    public function setOtherUser(?User $other_user): self
    {
        $this->other_user = $other_user;

        return $this;
    }
}
