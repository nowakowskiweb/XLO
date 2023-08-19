<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class UserController extends AbstractController
{
    private $entityManager;
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager,UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    #[Route('/user', name: 'app_user')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function index(): Response
    {
        return $this->render('@pages/user.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    #[Route('/user/favorites', name: 'user_favorites')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function favorite(Request $request): Response
    {
        $user = $this->getUser();

        // Pobieramy dane z formularza
        $page = $request->query->getInt('page', 1);

        // Pobieramy paginowane ogłoszenia z uwzględnieniem filtrów
        $announcements = $this->userRepository->findsFavoriteAnnouncementsPaginated($page, 10, $user);

        return $this->render('@pages/announcements-favorite.html.twig', [
            'announcements' => $announcements,
        ]);
    }

    #[Route('/user/favorite', name: 'user_favorite_add')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function addFavorite(Request $request): Response
    {
        // Pobieramy dane z formularza
        $page = $request->query->getInt('page', 1);

        // Pobieramy paginowane ogłoszenia z uwzględnieniem filtrów
        $announcements = $this->announcementRepository->findsFavoriteAnnouncementsPaginated($page, 10, $request);

        return $this->render('@pages/announcements-favorite.html.twig', [
            'announcements' => $announcements,
        ]);
    }
}
