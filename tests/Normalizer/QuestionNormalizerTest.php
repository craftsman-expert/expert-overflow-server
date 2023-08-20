<?php

namespace App\Tests\Normalizer;

use App\Entity;
use App\Normalizer\QuestionNormalizer;
use Helmich\JsonAssert\JsonAssertions;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuestionNormalizerTest extends WebTestCase
{
    use JsonAssertions;

    private mixed $questionEntity = null;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $this->questionEntity = new Entity\Question(
            author: $this->authorFactory(),
            title: 'Как правильно рендерить контент?',
            text: 'При нажатии на определённый элемент меню, должно отображаться определённый контент ...',
        );

        $this->questionEntity
            ->getComments()
            ->add(new Entity\QuestionComment(
                author: $this->authorFactory(),
                question: $this->questionEntity,
                text: 'This is the comment text',
            ));

        $this->questionEntity
            ->getComments()
            ->add(new Entity\QuestionComment(
                author: $this->authorFactory(),
                question: $this->questionEntity,
                text: 'This is the comment text',
            ));
    }

    public function testNormalize(): void
    {
        $normalizer = new QuestionNormalizer(
            tokenStorage: self::getContainer()->get('security.token_storage')
        );

        self::assertTrue($normalizer->supportsNormalization($this->questionEntity));

        $data = $normalizer->normalize($this->questionEntity, null, [
            'full',
            'checkSubscription',
        ]);

        self::assertJsonDocumentMatchesSchema(
            jsonDocument: json_encode($data, JSON_THROW_ON_ERROR),
            schema: $this->getSchema()
        );
    }

    protected function tearDown(): void
    {
        $this->questionEntity = null;

        parent::tearDown();
    }

    private function authorFactory(): Entity\User
    {
        return (new Entity\User('expert'))
            ->setFirstName('Игорь')
            ->setSurname('Попрядухин')
            ->setMiddleName('Игоревич');
    }

    private function getSchema(): array
    {
        return [
            '$schema' => 'http://json-schema.org/draft-07/schema#',
            'title' => 'Generated schema for Root',
            'type' => 'object',
            'properties' => [
                'id' => [
                    'type' => ['number', 'null'],
                ],
                'title' => [
                    'type' => 'string',
                ],
                'subscribersCount' => [
                    'type' => 'number',
                ],
                'answerCount' => [
                    'type' => 'number',
                ],
                'viewsCount' => [
                    'type' => 'number',
                ],
                'commentCount' => [
                    'type' => 'number',
                ],
                'preview' => [
                    'type' => 'string',
                ],
                'tags' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => [
                                'type' => ['number', 'null'],
                            ],
                            'name' => [
                                'type' => 'string',
                            ],
                            'description' => [
                                'type' => 'string',
                            ],
                            'questionsCount' => [
                                'type' => 'number',
                            ],
                            'subscribersCount' => [
                                'type' => 'number',
                            ],
                        ],
                        'required' => [
                            'id',
                            'name',
                            'description',
                            'questionsCount',
                            'subscribersCount',
                        ],
                    ],
                ],
                'author' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => [
                            'type' => ['number', 'null'],
                        ],
                        'uuid' => [
                            'type' => 'string',
                        ],
                        'firstName' => [
                            'type' => 'string',
                        ],
                        'surname' => [
                            'type' => 'string',
                        ],
                        'username' => [
                            'type' => 'string',
                        ],
                        'fullName' => [
                            'type' => 'string',
                        ],
                        'abbreviation' => ['type' => 'string'],
                        'avatar' => [],
                        'about' => ['type' => ['string', 'null']],
                    ],
                    'required' => [
                        'id',
                        'uuid',
                        'firstName',
                        'surname',
                        'username',
                        'fullName',
                        'abbreviation',
                        'avatar',
                        'about',
                    ],
                ],
                'createdAt' => [
                    'type' => ['string', 'object'],
                ],
                'updatedAt' => [
                ],
                'content' => [
                    'type' => 'string',
                ],
                'answers' => [
                    'type' => 'array',
                    'items' => [
                    ],
                ],
                'isSubscribed' => [
                    'type' => 'boolean',
                ],
            ],
            'required' => [
                'id',
                'title',
                'subscribersCount',
                'answerCount',
                'viewsCount',
                'commentCount',
                'preview',
                'tags',
                'author',
                'createdAt',
                'updatedAt',
                'content',
                'answers',
                'isSubscribed',
            ],
        ];
    }
}
