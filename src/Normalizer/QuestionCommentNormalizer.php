<?php

namespace App\Normalizer;

use App\Entity;
use App\Security\Voter\CommentVoter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Igor Popryadukhin <igorpopryadukhin@gmail.com>
 */
class QuestionCommentNormalizer implements NormalizerInterface
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly Security $security,
    )
    {
    }

    /**
     * @param Entity\QuestionComment $object
     */
    public function normalize($object, string $format = null, array $context = []): ?array
    {
        $authUser = $this->tokenStorage->getToken()?->getUser();
        $authorIsMe = $authUser instanceof Entity\User && $authUser->getId() === $object->getAuthor()->getId();

        return [
            'id' => $object->getId(),
            'text' => $object->getText(),
            'createdAt' => $object->getCreatedAt(),
            'author' => $this->authorNormalize($object->getAuthor()),
            'authorIsMe' => $authorIsMe,
            'isEditable' => $this->security->isGranted(
                attributes: CommentVoter::EDIT,
                subject: $object
            ),
            'canBeDeleted' => $this->security->isGranted(
                attributes: CommentVoter::DELETE,
                subject: $object
            ),
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Entity\QuestionComment;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            'object' => null,
            '*' => false,
            Entity\QuestionComment::class => true,
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
}
