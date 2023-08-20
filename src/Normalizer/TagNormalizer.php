<?php

namespace App\Normalizer;

use App\Entity;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Igor Popryadukhin <igorpopryadukhin@gmail.com>
 */
class TagNormalizer implements NormalizerInterface
{
    /**
     * @param Entity\Tag $object
     */
    public function normalize($object, string $format = null, array $context = []): ?array
    {
        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'description' => $object->getDescription(),
            'questionsCount' => $object->getQuestionsCount(),
            'subscribersCount' => $object->getSubscribersCount(),
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Entity\Tag;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            'object' => null,
            '*' => false,
            Entity\Tag::class => true,
        ];
    }
}
