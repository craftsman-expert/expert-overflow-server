<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

#[ORM\Table(name: 'questions')]
#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\JoinTable(name: 'questions_to_tags')]
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'questions', cascade: ['all'])]
    private ArrayCollection|PersistentCollection $tags;

    #[ORM\JoinTable(name: 'questions_to_subscribers')]
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'questions', cascade: ['all'])]
    private ArrayCollection|PersistentCollection $subscribers;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: QuestionComment::class)]
    #[ORM\OrderBy(['id' => 'ASC'])]
    private ArrayCollection|PersistentCollection $comments;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class)]
    #[ORM\OrderBy(['score' => 'ASC'])]
    private ArrayCollection|PersistentCollection $answers;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $answerCount = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $commentCount = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $subscribersCount = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $viewsCount = 0;

    #[ORM\Column(type: 'datetime')]
    private \DateTime|null $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private \DateTime|null $updatedAt = null;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: User::class)]
        private User $author,
        #[ORM\Column(type: 'string', nullable: true)]
        private string $title,
        #[ORM\Column(type: 'text', nullable: true)]
        private string $text,
    ) {
        $this->createdAt = new \DateTime();
        $this->tags = new ArrayCollection();
        $this->subscribers = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->answers = new ArrayCollection();

        // Автор становится подписчиком на свой же вопрос
        $this->subscribe($author);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): Question
    {
        $this->author = $author;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Question
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): Question
    {
        $this->text = $text;

        return $this;
    }

    public function getAnswerCount(): int
    {
        return $this->answerCount;
    }

    public function setAnswerCount(int $answerCount): Question
    {
        $this->answerCount = $answerCount;

        return $this;
    }

    public function getCommentCount(): int
    {
        return $this->commentCount;
    }

    public function setCommentCount(int $commentCount): Question
    {
        $this->commentCount = $commentCount;

        return $this;
    }

    public function getSubscribersCount(): int
    {
        return $this->subscribersCount;
    }

    public function getViewsCount(): int
    {
        return $this->viewsCount;
    }

    public function setViewsCount(int $viewsCount): Question
    {
        $this->viewsCount = $viewsCount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): Question
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): Question
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTags(): ArrayCollection|PersistentCollection
    {
        return $this->tags;
    }

    public function subscribe(User $user): Question
    {
        if (!$this->subscribers->contains($user)) {
            ++$this->subscribersCount;
            $this->subscribers->add($user);
        }

        return $this;
    }

    public function unsubscribe(User $user): Question
    {
        if ($this->subscribers->contains($user)) {
            --$this->subscribersCount;
            $this->subscribers->removeElement($user);
        }

        return $this;
    }

    public function getSubscribers(): ArrayCollection|PersistentCollection
    {
        return $this->subscribers;
    }

    public function getComments(): ArrayCollection|PersistentCollection
    {
        return $this->comments;
    }

    public function getAnswers(): ArrayCollection|PersistentCollection
    {
        return $this->answers;
    }
}
