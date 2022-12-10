<?php

namespace App\Entity;

use App\Repository\GalleryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GalleryRepository::class)]
class Gallery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $date = null;

    #[ORM\Column(nullable: true)]
    private ?int $last_update = null;

    #[ORM\OneToMany(mappedBy: 'gallery', targetEntity: MessageGallery::class, orphanRemoval: true)]
    private Collection $messages;

    #[ORM\ManyToOne(inversedBy: 'galleries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getLastUpdate(): ?int
    {
        return $this->last_update;
    }

    public function setLastUpdate(int $last_update): self
    {
        $this->last_update = $last_update;

        return $this;
    }

    /**
     * @return Collection<int, MessageGallery>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(MessageGallery $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setGallery($this);
        }

        return $this;
    }

    public function removeMessage(MessageGallery $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getGallery() === $this) {
                $message->setGallery(null);
            }
        }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
