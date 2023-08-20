<?php

namespace App\Entity;

use App\Repository\QuestionCommentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'questions_comments')]
#[ORM\Entity(repositoryClass: QuestionCommentRepository::class)]
class QuestionComment implements CommentInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: User::class)]
        private User $author,
        #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'comments')]
        private Question $question,
        #[ORM\Column(type: 'text')]
        private string $text
    ) {
        $question->setCommentCount($question->getCommentCount() + 1);

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

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): QuestionComment
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
