<?php

namespace App\Entity;

use App\Repository\AnswerCommentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'answers_comments')]
#[ORM\Entity(repositoryClass: AnswerCommentRepository::class)]
class AnswerComment implements CommentInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private \DateTime $updatedAt;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: User::class)]
        private User $author,
        #[ORM\ManyToOne(targetEntity: Answer::class, inversedBy: 'comments')]
        private Answer $answer,
        #[ORM\Column(type: 'text')]
        private string $text
    ) {
        $answer->setCommentCount($answer->getCommentCount() + 1);
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getAnswer(): Answer
    {
        return $this->answer;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): AnswerComment
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): AnswerComment
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
