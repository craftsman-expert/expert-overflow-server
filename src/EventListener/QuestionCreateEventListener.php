<?php

namespace App\EventListener;

use App\Entity;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Entity\Question::class)]
class QuestionCreateEventListener
{
    public function prePersist(Entity\Question $question): void
    {
        $iterator = $question->getTags()->getIterator();
        $iterator->rewind();

        while ($iterator->valid()) {
            /** @var Entity\Tag $tag */
            $tag = $iterator->current();

            $tag->setQuestionsCount($tag->getQuestionsCount() + 1);

            $iterator->next();
        }
    }
}
