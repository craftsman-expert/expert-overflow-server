<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

#[ORM\Table(name: 'answers')]
#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'answer', targetEntity: AnswerComment::class)]
    #[ORM\OrderBy(['id' => 'ASC'])]
    private ArrayCollection|PersistentCollection $comments;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $commentCount = 0;

    /**
     * Счёт, количество голосов за ответ.
     */
    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $score = 0;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private \DateTime|null $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private \DateTime|null $updatedAt = null;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: User::class)]
        private User $user,
        #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'answers')]
        private Question $question,
        #[ORM\Column(type: 'text', nullable: true)]
        private string $text,
    ) {
        $this->comments = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComments(): ArrayCollection|PersistentCollection
    {
        return $this->comments;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function getCommentCount(): int
    {
        return $this->commentCount;
    }

    public function setCommentCount(int $commentCount): Answer
    {
        $this->commentCount = $commentCount;

        return $this;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): Answer
    {
        $this->score = $score;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): Answer
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): Answer
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
