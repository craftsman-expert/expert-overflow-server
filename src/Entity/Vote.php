<?php

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'votes')]
#[ORM\UniqueConstraint(
    name: 'VOTES_UNIQUE',
    columns: ['user_id', 'answer_id']
)]
#[ORM\Entity(repositoryClass: VoteRepository::class)]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Answer::class)]
    private Answer $answer;

    #[ORM\Column(type: 'datetime')]
    private \DateTime|null $createdAt;

    public function __construct(
        User $user,
        Answer $answer
    ) {
        $this->user = $user;
        $this->answer = $answer;
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getAnswer(): Answer
    {
        return $this->answer;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }
}
