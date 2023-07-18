<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AnnouncementController extends BaseController
{
    #[Route('/announcement', name: 'announcement_index')]
    public function index(): Response
    {
        return $this->render('@pages/announcement.html.twig');
    }

    #[Route('/announcement/add', name: 'announcement_add')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function store(): Response
    {
        return $this->render('@pages/announcement.html.twig', [
            'controller_name' => 'AnnouncementController',
        ]);
    }
}
