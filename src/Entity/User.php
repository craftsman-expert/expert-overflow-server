<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue('IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid', unique: true, nullable: false)]
    private UuidInterface|null $uuid = null;

    #[ORM\ManyToOne(targetEntity: UserLocation::class)]
    private UserLocation|null $location = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserSocialNetwork::class)]
    private ArrayCollection|PersistentCollection $socialNetworks;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserSession::class)]
    private ArrayCollection|PersistentCollection $sessions;

    #[ORM\ManyToMany(targetEntity: Question::class, mappedBy: 'subscribers')]
    private ArrayCollection|PersistentCollection $questions;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    #[ORM\Column(nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(nullable: true)]
    private ?string $surname = null;

    #[ORM\Column(nullable: true)]
    private ?string $middleName = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $about = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column(type: 'text', unique: true, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'integer', nullable: true, options: ['default' => 0])]
    private int $followers = 0;
    #[ORM\Column(type: 'integer', nullable: true, options: ['default' => 0])]
    private int $following = 0;

    #[ORM\Column(type: 'boolean', options: ['default' => 0])]
    private bool $blocked = false;

    public function __construct(
        string $username
    ) {
        $this->username = $username;
        $this->socialNetworks = new ArrayCollection();
        $this->sessions = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->uuid = Uuid::uuid4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getAbbreviation(): ?string
    {
        $chunks = [];

        if (!empty($this->surname)) {
            $chunks[] = mb_substr($this->surname, 0, 1) ?? '';
        }

        if (!empty($this->firstName)) {
            $chunks[] = mb_substr($this->firstName, 0, 1) ?? '';
        }

        $name = implode('', $chunks);

        if (!empty($name)) {
            return $name;
        }

        return '';
    }

    public function getFullName(): ?string
    {
        return implode(' ', [
            $this->surname,
            $this->firstName,
            $this->middleName,
        ]);
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->username;
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

    public function setRoles(array $roles): static
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

    public function setPassword(string|null $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getSocialNetworks(): ArrayCollection|PersistentCollection
    {
        return $this->socialNetworks;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): User
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): User
    {
        $this->surname = $surname;

        return $this;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(?string $middleName): User
    {
        $this->middleName = $middleName;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): User
    {
        $this->about = $about;

        return $this;
    }

    public function getSessions(): ArrayCollection|PersistentCollection
    {
        return $this->sessions;
    }

    public function getLocation(): ?UserLocation
    {
        return $this->location;
    }

    public function setLocation(?UserLocation $location): User
    {
        $this->location = $location;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getFollowers(): int
    {
        return $this->followers;
    }

    public function setFollowers(int $followers): User
    {
        $this->followers = $followers;

        return $this;
    }

    public function getFollowing(): int
    {
        return $this->following;
    }

    public function setFollowing(int $following): User
    {
        $this->following = $following;

        return $this;
    }

    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    public function setBlocked(bool $blocked): User
    {
        $this->blocked = $blocked;

        return $this;
    }
}
