<?php

namespace App\Controller;

use App\Entity;
use App\Exception\Http\NotFoundException;
use App\Repository\QuestionCommentRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/questions')]
class QuestionCommentController extends AbstractController
{
    #[Route(
        path: '/{questionId}/comments',
        requirements: [
            'questionId' => '\d+',
        ],
        methods: 'POST'
    )]
    public function addComment(
        int $questionId,
        Request $request,
        QuestionRepository $questionRepository,
        EntityManagerInterface $entityManager,
        NormalizerInterface $normalizer
    ): JsonResponse {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $question = $questionRepository->find($questionId);

        if (is_null($question)) {
            throw new NotFoundException('Указанный вами вопрос не найден!');
        }

        /** @var Entity\User $user */
        $user = $this->getUser();

        $comment = new Entity\QuestionComment(
            author: $user,
            question: $question,
            text: $request->get('text')
        );

        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->json($normalizer->normalize($comment));
    }

    #[Route(
        path: '/{questionId}/comments',
        requirements: [
            'questionId' => '\d+',
        ],
        methods: 'GET'
    )]
    public function getComments(
        int $questionId,
        Request $request,
        QuestionCommentRepository $questionCommentRepository,
        NormalizerInterface $normalizer
    ): JsonResponse {

        $offset = (int)$request->get('offset', 0);
        $limit = (int)$request->get('limit', 30);

        if ($limit > 1000) {
            $limit = 1000;
        }

        $paginator = $questionCommentRepository->findWithOffset(
            questionId: $questionId,
            offset: $offset,
            limit: $limit,
        );

        $total = $paginator->getCount();

        if ($offset > $total) {
            $offset = $total;
        }

        return $this->json(
            data: [
                'meta' => [
                    'offset' => $offset,
                    'limit' => $limit,
                    'total' => $total,
                    'dataType' => 'array',
                ],
                'data' => array_map(
                    callback: static fn ($comment) => $normalizer->normalize($comment),
                    array: $paginator->getResult()
                ),
            ]
        );
    }
}
