<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

#[ORM\Table(name: 'tags')]
#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    #[ORM\ManyToMany(targetEntity: Question::class, mappedBy: 'tags')]
    private ArrayCollection|PersistentCollection $questions;

    #[ORM\Column(type: 'integer')]
    private int $subscribersCount = 0;

    #[ORM\Column(type: 'integer')]
    private int $questionsCount = 0;

    public function __construct(
        #[ORM\Column(type: 'string')]
        private string $name,
        #[ORM\Column(type: 'text')]
        private string|null $description = null
    ) {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Tag
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Tag
    {
        $this->description = $description;

        return $this;
    }

    public function getSubscribersCount(): int
    {
        return $this->subscribersCount;
    }

    public function setSubscribersCount(int $subscribersCount): Tag
    {
        $this->subscribersCount = $subscribersCount;

        return $this;
    }

    public function getQuestionsCount(): int
    {
        return $this->questionsCount;
    }

    public function setQuestionsCount(int $questionsCount): Tag
    {
        $this->questionsCount = $questionsCount;

        return $this;
    }

    public function getQuestions(): ArrayCollection|PersistentCollection
    {
        return $this->questions;
    }
}
