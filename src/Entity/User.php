<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Service\UserEdition;
use App\Service\UserEditionValidation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 16)]
    private ?string $name = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $signature = null;

    #[ORM\Column]
    private ?int $notifsNb = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: MessageTopic::class, orphanRemoval: true)]
    private Collection $messages;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: MessageGallery::class, orphanRemoval: true)]
    private Collection $messageGalleries;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notification::class, orphanRemoval: true)]
    private Collection $notifications;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Gallery::class, orphanRemoval: true)]
    private Collection $galleries;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Topic::class, orphanRemoval: true)]
    private Collection $topics;

    #[ORM\Column]
    private ?int $messages_nb = null;

    #[ORM\Column]
    private ?int $date = null;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->messageGalleries = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->galleries = new ArrayCollection();
        $this->topics = new ArrayCollection();
    }
    
    public function beforeFirstSave(): self
    {
        $this->messages_nb = 0;
        $this->date = (new \DateTime('now'))->getTimestamp();
        $this->photo = 'default-pp.png';
        $this->notifsNb = 0;
        
        return $this;
    }
    
    /**
     * 
     * @param UserEdition $userEdition
     * @return ?string renvoie une erreur s'il y en a une
     */
    public function editSafely(UserEdition $userEdition, UserEditionValidation $u): ?string
    {
        $name = $userEdition->getName();
        $email = $userEdition->getEmail();
        $password = $userEdition->getPassword();
        $city = $userEdition->getCity();
        $country = $userEdition->getCountry();
        $signature = $userEdition->getSignature();
        $photo = $userEdition->getPhoto();
        $error = null;
        
        if ($name != null) {
            if ($u->fieldIsValid('name', $name)) {
                $u->reserveName($name, $this->name);
                $this->name = $name;
            } else {
                $error = 'Le pseudo '.$name.'n\'est pas disponible';
            }
        } else if ($email != null) {
            if ($u->fieldIsValid('email', $email)) {
                $this->email = $email;
            } else {
                $error = 'L\'adresse mail '.$email.' n\'a pas pu être vérifiée';
            }
        } else if ($password != null) {
            $this->password = $u->hashPassword($this, $password);
        } else if ($city != null || $country != null) {
            if ($city != null && $country != null) {
                $this->city = $city;
                $this->country = $country;
            } else {
                $error = 'Si vous définissez une localisation, vous devez préciser la ville et le pays';
            }
        } else if ($signature != null) {
            $this->signature = $signature;
        } else if ($photo != null) {
            //TODO
        }
        
        return $error;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getNotifsNb(): ?int
    {
        return $this->notifsNb;
    }

    public function setNotifsNb(int $notifsNb): self
    {
        $this->notifsNb = $notifsNb;

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
            $message->setUserId($this);
        }

        return $this;
    }

    public function removeMessage(MessageTopic $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUserId() === $this) {
                $message->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MessageGallery>
     */
    public function getMessageGalleries(): Collection
    {
        return $this->messageGalleries;
    }

    public function addMessageGallery(MessageGallery $messageGallery): self
    {
        if (!$this->messageGalleries->contains($messageGallery)) {
            $this->messageGalleries->add($messageGallery);
            $messageGallery->setUser($this);
        }

        return $this;
    }

    public function removeMessageGallery(MessageGallery $messageGallery): self
    {
        if ($this->messageGalleries->removeElement($messageGallery)) {
            // set the owning side to null (unless already changed)
            if ($messageGallery->getUser() === $this) {
                $messageGallery->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Gallery>
     */
    public function getGalleries(): Collection
    {
        return $this->galleries;
    }

    public function addGallery(Gallery $gallery): self
    {
        if (!$this->galleries->contains($gallery)) {
            $this->galleries->add($gallery);
            $gallery->setUser($this);
        }

        return $this;
    }

    public function removeGallery(Gallery $gallery): self
    {
        if ($this->galleries->removeElement($gallery)) {
            // set the owning side to null (unless already changed)
            if ($gallery->getUser() === $this) {
                $gallery->setUser(null);
            }
        }

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
            $topic->setUser($this);
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->removeElement($topic)) {
            // set the owning side to null (unless already changed)
            if ($topic->getUser() === $this) {
                $topic->setUser(null);
            }
        }

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

    public function getDate(): ?int
    {
        return $this->date;
    }

    public function setDate(int $date): self
    {
        $this->date = $date;

        return $this;
    }
    
    public function incrementMessagesNb(): self
    {
        $this->messages_nb++;
        return $this;
    }
}
