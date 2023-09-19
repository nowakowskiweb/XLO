<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestFormType;
use App\Form\UserEditProfileType;
use App\Repository\AnnouncementRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository         $userRepository,
        private AnnouncementRepository $announcementRepository,
        private ResetPasswordHelperInterface $resetPasswordHelper,
    )
    {
    }

    #[Route('/edit', name: 'user_edit')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function accountEdit(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserEditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);  // to jest opcjonalne jeśli użytkownik jest już zarządzany przez EntityManagera
            $this->entityManager->flush();

            return $this->render('@pages/user_edit.html.twig', [
                'userEditForm' => $form->createView(),
                'user' => $user,
                'isSubmitted' => true
            ]);
        }

        return $this->render('@pages/user_edit.html.twig', [
            'userEditForm' => $form->createView(),
            'user' => $user,
            'isSubmitted' => $form->isSubmitted()
        ]);
    }


    #[Route('/favorites', name: 'user_favorites')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function favoriteAnnouncements(Request $request): Response
    {
        $user = $this->getUser();

        $page = $request->query->getInt('page', 1);

        // Pobieramy paginowane ogłoszenia z uwzględnieniem filtrów
        $announcements = $this->userRepository->findsFavoriteAnnouncementsPaginated($page, 10, $user);

        return $this->render('@pages/user_favorites.html.twig', [
            'announcements' => $announcements,
        ]);
    }

    #[Route('/posted', name: 'user_posted')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function postedAnnouncements(Request $request): Response
    {
        $user = $this->getUser();
        $page = $request->query->getInt('page', 1);

        $announcements = $this->announcementRepository->findsPostedAnnouncementsPaginated($page, 10, $user);

        return $this->render('@pages/user_posted.html.twig', [
            'announcements' => $announcements,
        ]);
    }

    #[Route('/messages', name: 'user_messages')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function messages(Request $request): Response
    {
        return $this->render('@pages/user_messages.html.twig');
    }

    #[Route('/user/favorite/add', name: 'user_favorite_add')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function favoriteAdd(Request $request): Response
    {
        return $this->render('@pages/user_favorite.html.twig');
    }

    #[Route('/delete', name: 'user_delete')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function accountDelete(Request $request): Response
    {
        return $this->render('@pages/user_favorite.html.twig');
    }
}
