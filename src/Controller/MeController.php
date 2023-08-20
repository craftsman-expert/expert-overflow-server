<?php

namespace App\Controller;

use App\Entity;
use App\Service\Avatar\AvatarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MeController extends AbstractController
{
    #[Route('/me', name: 'me')]
    public function index(
        Security $security,
        AvatarService $avatarService
    ): JsonResponse {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var Entity\User $thisUser */
        $thisUser = $security->getUser();

        return $this->json([
            'id' => $thisUser->getId(),
            'uuid' => $thisUser->getUuid()->toString(),
            'username' => $thisUser->getUsername(),
            'socialNetworks' => $thisUser->getSocialNetworks()->toArray(),
            'avatar' => $avatarService->getAvatar($thisUser->getUuid()->toString()),
            'abbreviation' => $thisUser->getAbbreviation(),
        ]);
    }
}
