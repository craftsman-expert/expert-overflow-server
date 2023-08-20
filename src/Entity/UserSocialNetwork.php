<?php

namespace App\Entity;

use App\Repository\UserSocialNetworkRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'users_social_networks')]
#[ORM\UniqueConstraint(
    name: 'USER_SOCIAL_NETWORK_UNIQUE',
    columns: ['social_network_id', 'user_id', 'user_external_id']
)]
#[ORM\Entity(repositoryClass: UserSocialNetworkRepository::class)]
class UserSocialNetwork
{
    #[ORM\Id]
    #[ORM\GeneratedValue('IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: SocialNetwork::class)]
    private SocialNetwork|null $socialNetwork;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'socialNetworks')]
    private User|null $user;

    #[ORM\Column(nullable: false)]
    private ?string $userExternalId;

    public function __construct(
        SocialNetwork $socialNetwork,
        User $user,
        string $userExternalId
    ) {
        $this->socialNetwork = $socialNetwork;
        $this->user = $user;
        $this->userExternalId = $userExternalId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSocialNetwork(): ?SocialNetwork
    {
        return $this->socialNetwork;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getUserExternalId(): ?string
    {
        return $this->userExternalId;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(?string $accessToken): UserSocialNetwork
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): UserSocialNetwork
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }
}
