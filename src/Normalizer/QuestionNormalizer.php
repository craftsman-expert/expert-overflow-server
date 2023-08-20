<?php

namespace App\Normalizer;

use App\Entity;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Igor Popryadukhin <igorpopryadukhin@gmail.com>
 */
class QuestionNormalizer implements NormalizerInterface
{
    public function __construct(private readonly TokenStorageInterface $tokenStorage)
    {
    }

    /**
     * @param Entity\Question $object
     */
    public function normalize($object, string $format = null, array $context = []): ?array
    {
        $normalized = [
            'id' => $object->getId(),
            'title' => $object->getTitle(),
            'subscribersCount' => $object->getSubscribersCount(),
            'answerCount' => $object->getAnswerCount(),
            'viewsCount' => $object->getViewsCount(),
            'commentCount' => $object->getCommentCount(),
            'preview' => $this->makePreview($object->getText(), 200),
            'tags' => array_map([$this, 'tagNormalize'], $object->getTags()->toArray()),
            'author' => $this->authorNormalize($object->getAuthor()),
            'createdAt' => $object->getCreatedAt(),
            'updatedAt' => $object->getUpdatedAt(),
        ];

        if (in_array('full', $context, true)) {
//            $normalized['comments'] = array_map([$this, 'commentNormalize'], $object->getComments()
//                ->matching(Criteria::create()->setMaxResults(10))
//                ->toArray()
//            );
            $normalized['author'] = $this->authorNormalize($object->getAuthor());
            $normalized['content'] = $object->getText();
            $normalized['answers'] = array_map([$this, 'answerNormalize'], $object->getAnswers()->toArray());
        }

        if (in_array('checkSubscription', $context, true)) {
            $normalized['isSubscribed'] = $this->isSubscribed($object);
        }

        return $normalized;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Entity\Question;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            'object' => null,             // Не поддерживает какие-либо классы или интерфейсы
            '*' => false,                 // Поддерживает любые другие типы, но результат не кэшируется.
            Entity\Question::class => true, // Поддерживает Entity\Question, результат кэшируется.
        ];
    }

    private function makePreview(string $text, int $length): string
    {
        $textLen = strlen($text);

        $dots = '';

        if ($textLen > $length) {
            $dots = ' ...';
        }

        $text = html_entity_decode($text, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5);
        $text = strip_tags($text);
        $text = mb_substr($text, 0, $length);
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text, '!,.-| ') . $dots;
    }

    private function tagNormalize(Entity\Tag $tag): array
    {
        return [
            'id' => $tag->getId(),
            'name' => $tag->getName(),
            'description' => $tag->getDescription(),
            'questionsCount' => $tag->getQuestionsCount(),
            'subscribersCount' => $tag->getSubscribersCount(),
        ];
    }

    private function commentNormalize(Entity\QuestionComment|Entity\AnswerComment $comment): array
    {
        return [
            'id' => $comment->getId(),
            'text' => $comment->getText(),
            'createdAt' => $comment->getCreatedAt(),
            'author' => $this->authorNormalize($comment->getAuthor()),
        ];
    }

    private function authorNormalize(Entity\User $author): array
    {
        return [
            'id' => $author->getId(),
            'uuid' => $author->getUuid()->toString(),
            'firstName' => $author->getFirstName(),
            'surname' => $author->getSurname(),
            'username' => $author->getUsername(),
            'fullName' => $author->getFullName(),
            'abbreviation' => $author->getAbbreviation(),
            'avatar' => null,
            'about' => $author->getAbout(),
        ];
    }

    private function answerNormalize(Entity\Answer $answer): array
    {
        return [
            'id' => $answer->getId(),
            'score' => $answer->getScore(),
            'text' => $answer->getText(),
            'author' => $this->authorNormalize($answer->getUser()),
            'comments' => array_map([$this, 'commentNormalize'], $answer->getComments()->toArray()),
            'commentsCount' => $answer->getCommentCount(),
            'createdAt' => $answer->getCreatedAt(),
            'updatedAt' => $answer->getUpdatedAt(),
        ];
    }

    private function isSubscribed(Entity\Question $question): bool
    {
        $user = $this->tokenStorage->getToken()?->getUser();

        if (!$user instanceof Entity\User) {
            return false;
        }

        return $question->getSubscribers()->contains($user);
    }
}
