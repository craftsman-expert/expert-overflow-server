<?php

namespace App\Controller;

use App\Service\Avatar\AvatarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(AvatarService $avatarService): JsonResponse
    {
        $avatarService->download(
            url: 'https://sun1-95.userapi.com/s/v1/ig2/831-DVGsEzUPDSyag1CVdsIlg0C8T0Ig7CBSdXpBjS5fKR83DLUXdCU2vYO-zgbuoVyIFI5FKp3MfHZsF0e0VSH5.jpg?size=200x200&quality=95&crop=53,22,501,501&ava=1',
            uuid: '7265fecc-2452-419e-9669-6c2b01c0f021'
        );

        return $this->json([]);
    }
}
