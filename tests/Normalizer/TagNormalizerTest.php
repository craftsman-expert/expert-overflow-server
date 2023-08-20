<?php

namespace App\Tests\Normalizer;

use App\Entity\Tag;
use App\Normalizer\TagNormalizer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TagNormalizerTest extends WebTestCase
{
    private mixed $tagEntity = null;

    protected function setUp(): void
    {
        $this->tagEntity = new Tag(
            name: 'PHP',
            description: 'Операционная система — комплекс программ, ...'
        );
    }

    public function testNormalize(): void
    {
        $normalizer = new TagNormalizer();

        $data = $normalizer->normalize($this->tagEntity);

        self::assertTrue($normalizer->supportsNormalization($this->tagEntity));
        self::assertArrayHasKey('id', $data);
        self::assertArrayHasKey('name', $data);
        self::assertArrayHasKey('description', $data);
        self::assertArrayHasKey('questionsCount', $data);
        self::assertArrayHasKey('subscribersCount', $data);
    }

    protected function tearDown(): void
    {
        $this->tagEntity = null;
    }
}
