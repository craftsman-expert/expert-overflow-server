<?php

namespace App\Controller;

use App\Entity;
use App\Exception\Http\NotFoundException;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/questions')]
class QuestionController extends AbstractController
{
    #[Route(methods: 'GET')]
    public function getAll(
        Request $request,
        QuestionRepository $questionRepository,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        $perPage = $request->get('per-page', 10);
        $page = $request->get('page', 1);

        $questionsPaginator = $questionRepository->findByCriteria();
        $total = $questionsPaginator->getCount();

        return $this->json([
            'meta' => [
                'paginationPages' => ceil($total / $perPage),
                'paginationPage' => $page,
                'paginationPerPage' => $perPage,
                'total' => $total,
                'dataType' => 'array',
            ],
            'data' => array_map(static fn ($question) => $normalizer->normalize($question), $questionsPaginator->getResult()),
        ]);
    }

    #[Route(
        path: '/{questionId}',
        methods: 'GET'
    )]
    public function getById(
        int $questionId,
        QuestionRepository $questionRepository,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        $question = $questionRepository->find($questionId);

        if (is_null($question)) {
            throw new NotFoundException('Указанный вами вопрос не найден!');
        }

        return $this->json($normalizer->normalize($question, null, [
            'full',
            'checkSubscription', // Проверить подписку
        ]));
    }

    #[Route(
        path: '/{questionId}/{action}',
        requirements: [
            'questionId' => '\d+',
            'action' => 'subscribe|unsubscribe',
        ],
        methods: 'POST'
    )]
    public function subscribeOrUnsubscribe(
        int $questionId,
        string $action,
        QuestionRepository $questionRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $question = $questionRepository->find($questionId);

        if (is_null($question)) {
            throw new NotFoundException('Указанный вами вопрос не найден!');
        }

        /** @var Entity\User $user */
        $user = $this->getUser();

        switch ($action) {
            case 'subscribe':
                $question->subscribe($user);
                break;

            case 'unsubscribe':
                $question->unsubscribe($user);
        }

        $entityManager->flush();

        return $this->json([
            'subscribersCount' => $question->getSubscribersCount(),
        ]);
    }
}
