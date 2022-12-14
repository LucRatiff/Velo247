<?php

namespace App\Entity;

use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TopicRepository::class)]
class Topic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'topics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SubCategory $sub_category = null;

    #[ORM\Column]
    private ?int $messages_nb = null;

    #[ORM\OneToMany(mappedBy: 'topic', targetEntity: MessageTopic::class, orphanRemoval: true)]
    private Collection $messages;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?MessageTopic $last_message = null;

    #[ORM\Column]
    private ?bool $locked = null;

    #[ORM\Column]
    private ?bool $pinned = null;

    #[ORM\Column]
    private ?int $views_nb = null;

    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $new_messages_views_users;

    #[ORM\ManyToOne(inversedBy: 'topics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?MessageTopic $first_message = null;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->new_messages_views_users = new ArrayCollection();
    }
    
    public function hydrateFirst(int $date, string $title, string $slug,
            SubCategory $subCategory, User $user, MessageTopic $message): self
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->sub_category = $subCategory;
        $this->messages_nb = 1;
        $this->user = $user;
        $this->locked = false;
        $this->pinned = false;
        $this->views_nb = 0;
        $this->date = $date;
        $this->first_message = $message;
        $this->last_message = $message;
        
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getSubCategory(): ?SubCategory
    {
        return $this->sub_category;
    }

    public function setSubCategory(?SubCategory $sub_category): self
    {
        $this->sub_category = $sub_category;

        return $this;
    }

    public function getMessagesNb(): ?int
    {
        return $this->messages_nb;
    }

    public function setMessagesNb(int $messages_nb): self
    {
        $this->messages_nb = $messages_nb;

        return $this;
    }

    /**
     * @return Collection<int, MessageTopic>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(MessageTopic $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setTopic($this);
        }
        
        $this->last_message = $message;
        $this->messages_nb++;

        return $this;
    }

    public function removeMessage(MessageTopic $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getTopic() === $this) {
                $message->setTopic(null);
            }
            $this->messages_nb--;
        }

        return $this;
    }

    public function getLastMessage(): ?MessageTopic
    {
        return $this->last_message;
    }

    public function setLastMessage(MessageTopic $last_message): self
    {
        $this->last_message = $last_message;

        return $this;
    }

    public function isLocked(): ?bool
    {
        return $this->locked;
    }

    public function setLocked(bool $locked): self
    {
        $this->locked = $locked;

        return $this;
    }

    public function isPinned(): ?bool
    {
        return $this->pinned;
    }

    public function setPinned(bool $pinned): self
    {
        $this->pinned = $pinned;

        return $this;
    }

    public function getViewsNb(): ?int
    {
        return $this->views_nb;
    }

    public function setViewsNb(int $views_nb): self
    {
        $this->views_nb = $views_nb;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getNewMessagesViewsUsers(): Collection
    {
        return $this->new_messages_views_users;
    }

    public function addNewMessagesViewsUser(User $newMessagesViewsUser): self
    {
        if (!$this->new_messages_views_users->contains($newMessagesViewsUser)) {
            $this->new_messages_views_users->add($newMessagesViewsUser);
        }

        return $this;
    }

    public function removeNewMessagesViewsUser(User $newMessagesViewsUser): self
    {
        $this->new_messages_views_users->removeElement($newMessagesViewsUser);

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

    public function getFirstMessage(): ?MessageTopic
    {
        return $this->first_message;
    }

    public function setFirstMessage(?MessageTopic $first_message): self
    {
        $this->first_message = $first_message;

        return $this;
    }
}
