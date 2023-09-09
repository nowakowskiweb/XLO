<?php

namespace App\Controller;

use App\Form\UserEditProfileType;
use App\Repository\AnnouncementRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;


class UserController extends AbstractController
{
    private $entityManager;
    private $userRepository;
    private $announcementRepository;


    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, AnnouncementRepository $announcementRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->announcementRepository = $announcementRepository;

    }

    #[Route('/user/edit', name: 'user_edit')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function accountEdit(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserEditProfileType::class, $user);
        $form->handleRequest($request);
        $isSubmitted = $form->isSubmitted();

        if ($isSubmitted && $form->isValid()) {
            $this->entityManager->persist($user);  // to jest opcjonalne jeśli użytkownik jest już zarządzany przez EntityManagera
            $this->entityManager->flush();

            return $this->render('@pages/user-edit.html.twig', [
                'userEditForm' => $form->createView(),
                'user' => $user,
                'isSubmitted' => false
            ]);
        }

        return $this->render('@pages/user-edit.html.twig', [
            'userEditForm' => $form->createView(),
            'user' => $user,
            'isSubmitted' => $isSubmitted
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

        return $this->render('@pages/user-posted.html.twig', [
            'announcements' => $announcements,
        ]);
    }

    #[Route('/user/messages', name: 'user_messages')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function messages(Request $request): Response
    {
        return $this->render('@pages/user-messages.html.twig');
    }

    #[Route('/user/password', name: 'user_password')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function password(Request $request): Response
    {
        return $this->render('@pages/user-messages.html.twig');
    }

    #[Route('/user/delete', name: 'user_delete')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function accountDelete(Request $request, VerifyEmailHelperInterface $verifyEmailHelper): Response
    {
        $user = $this->userRepository->find($request->query->get('id'));
        if (!$user) {
            throw $this->createNotFoundException();
        }

        try {
            $verifyEmailHelper->validateEmailConfirmation(
                $request->getUri(),
                $user->getId(),
                $user->getEmail()
            );
        } catch (VerifyEmailExceptionInterface $e) {
            return new Response($e->getReason());
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();

        $this->addFlash('success', 'Twoje konto zostało usunięte.');

        return $this->redirectToRoute('homepage');  // przekieruj do strony głównej lub innej strony
    }


    #[Route('/user/favorite/add', name: 'user_favorite_add')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function favoriteAdd(Request $request): Response
    {
        return $this->render('@pages/user-favorite.html.twig');

    }
}
