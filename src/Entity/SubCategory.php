<?php

namespace App\Entity;

use App\Repository\SubCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubCategoryRepository::class)]
class SubCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $messages_nb = null;

    #[ORM\OneToMany(mappedBy: 'sub_category_id', targetEntity: Topic::class, orphanRemoval: true)]
    private Collection $topics;

    #[ORM\ManyToOne(inversedBy: 'subCategories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\Column]
    private ?int $topics_nb = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?MessageTopic $last_message = null;

    public function __construct()
    {
        $this->topics = new ArrayCollection();
    }
    
    public function hydrate(string $name, Category $category, int $position, ?string $description): self
    {
        $this->name = $name;
        $this->category = $category;
        $this->position = $position;
        $this->description = $description;
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

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

    public function getMessagesNb(): ?int
    {
        return $this->messages_nb;
    }

    public function setMessages(int $messages_nb): self
    {
        $this->messages_nb = $messages_nb;

        return $this;
    }
    
    public function addMessage(): self
    {
        $this->messages_nb++;

        return $this;
    }

    /**
     * @return Collection<int, Topic>
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics->add($topic);
            $topic->setSubCategoryId($this);
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->removeElement($topic)) {
            // set the owning side to null (unless already changed)
            if ($topic->getSubCategoryId() === $this) {
                $topic->setSubCategoryId(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getTopicsNb(): ?int
    {
        return $this->topics_nb;
    }

    public function setTopicsNb(int $topics_nb): self
    {
        $this->topics_nb = $topics_nb;

        return $this;
    }

    public function getLastMessage(): ?MessageTopic
    {
        return $this->last_message;
    }

    public function setLastMessage(?MessageTopic $last_message): self
    {
        $this->last_message = $last_message;

        return $this;
    }
}
