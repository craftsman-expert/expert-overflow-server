<?php

namespace App\Controller;

use App\Entity;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/tags')]
class TagController extends AbstractController
{
    #[Route(methods: 'GET')]
    public function index(
        Request $request,
        TagRepository $tagRepository,
        NormalizerInterface $normalizer
    ): JsonResponse {
        $perPage = $request->get('per-page', 10);
        $page = $request->get('page', 1);

        $total = $tagRepository->count([]);

        return $this->json([
            'meta' => [
                'paginationPages' => ceil($total / $perPage),
                'paginationPage' => $page,
                'paginationPerPage' => $perPage,
                'total' => $total,
                'dataType' => 'array',
            ],
            'data' => array_map(static fn (Entity\Tag $tag) => $normalizer->normalize($tag), $tagRepository->findBy(
                criteria: [],
                limit: $perPage,
                offset: ($page * $perPage) - $perPage,
            )),
        ]);
    }
}
