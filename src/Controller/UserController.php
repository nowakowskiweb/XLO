<?php

namespace App\Controller;

use App\Repository\AnnouncementRepository;
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
    private $announcementRepository;


    public function __construct(EntityManagerInterface $entityManager,UserRepository $userRepository, AnnouncementRepository $announcementRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->announcementRepository = $announcementRepository;

    }

    #[Route('/user/account', name: 'user_account')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function accountEdit(): Response
    {
        return $this->render('@pages/user-account.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    #[Route('/user/favorites', name: 'user_favorites')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function favoriteAnnouncements(Request $request): Response
    {
        $user = $this->getUser();

        $page = $request->query->getInt('page', 1);

        // Pobieramy paginowane ogłoszenia z uwzględnieniem filtrów
        $announcements = $this->userRepository->findsFavoriteAnnouncementsPaginated($page, 10, $user);

        return $this->render('@pages/user-favorites.html.twig', [
            'announcements' => $announcements,
        ]);
    }

    #[Route('/user/posted', name: 'user_posted')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function postedAnnouncements(Request $request): Response
    {
        $user = $this->getUser();
        $page = $request->query->getInt('page', 1);

        $announcements = $this->announcementRepository->findsPostedAnnouncementsPaginated($page, 10, $user);

        return $this->render('@pages/user-posted.html.twig',[
            'announcements' => $announcements,
        ]);
    }

    #[Route('/user/messages', name: 'user_messages')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function messages(Request $request): Response
    {
        return $this->render('@pages/user-messages.html.twig');

    }

    #[Route('/user/delete', name: 'user_delete')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function accountDelete(Request $request): Response
    {
        return $this->render('@pages/user-delete.html.twig');

    }

    #[Route('/user/favorite/add', name: 'user_favorite_add')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function favoriteAdd(Request $request): Response
    {
        return $this->render('@pages/user-favorite.html.twig');

    }
}
