<?php

namespace App\Controller;

use App\Form\DeleteAccountConfirmationType;
use App\Form\Type\ConditionType;
use App\Form\Type\SortingType;
use App\Form\UserEditProfileType;
use App\Repository\AnnouncementRepository;
use App\Repository\UserRepository;
use App\Service\DeleteAccountService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository         $userRepository,
        private AnnouncementRepository $announcementRepository,
        private DeleteAccountService   $deleteAccountService,
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
        $favorites = $this->announcementRepository->findFavoritesForUser($this->getUser()->getId());


        return $this->render('@pages/user_favorites.html.twig', [
            'announcements' => $announcements,
            'favorites' => $favorites
        ]);
    }

    #[Route('/posted', name: 'user_posted')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function postedAnnouncements(Request $request): Response
    {
        $user = $this->getUser();
        $page = $request->query->getInt('page', 1);

        $announcements = $this->announcementRepository->findsPostedAnnouncementsPaginated($page, 10, $user);
        $favorites = $this->announcementRepository->findFavoritesForUser($this->getUser()->getId());

        return $this->render('@pages/user_posted.html.twig', [
            'announcements' => $announcements,
            'favorites' => $favorites,

        ]);
    }

    #[Route('/messages', name: 'user_messages')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function messages(Request $request): Response
    {
        return $this->render('@pages/user_messages.html.twig');
    }

    #[Route('/favorite/add/{announcementId}', name: 'user_favorite_add')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function favoriteAdd(Request $request, int $announcementId): Response
    {
        $user = $this->getUser();  // Pobierz aktualnie zalogowanego użytkownika
        $announcement = $this->announcementRepository->find($announcementId); // Pobierz ogłoszenie na podstawie ID

        if (!$announcement) {
            throw $this->createNotFoundException('Announcement not found');
        }

        // Dodaj ogłoszenie do ulubionych użytkownika
        $user->addFavoriteAnnouncement($announcement);

        // Aktualizuj bazę danych
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'Announcement added to favorites successfully!');


        $referer = $request->headers->get('referer');

        if (!$referer || !str_contains($referer, $request->getSchemeAndHttpHost())) {
            return $this->redirectToRoute('announcement_index');
        }

        return $this->redirect($referer);
    }

    #[Route('/favorite/delete/{announcementId}', name: 'user_favorite_delete')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function favoriteDelete(Request $request, int $announcementId): Response
    {
        $user = $this->getUser();  // Pobierz aktualnie zalogowanego użytkownika
        $announcement = $this->announcementRepository->find($announcementId); // Pobierz ogłoszenie na podstawie ID

        if (!$announcement) {
            throw $this->createNotFoundException('Announcement not found');
        }

        // Usuń ogłoszenie z ulubionych użytkownika
        $user->removeFavoriteAnnouncement($announcement);

        // Aktualizuj bazę danych
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'Announcement usuniete to favorites successfully!');


        $referer = $request->headers->get('referer');

        if (!$referer || !str_contains($referer, $request->getSchemeAndHttpHost())) {
            return $this->redirectToRoute('announcement_index');
        }

        return $this->redirect($referer);
    }

    #[Route('/delete', name: 'user_delete')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function accountDelete(Request $request): Response
    {
        return $this->render('@pages/user_favorite.html.twig');
    }

    #[Route('', name: 'mailer_account_delete')]
    public function request(MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_check_email_user');
        }

        $token = $this->deleteAccountService->createDeleteAccountRequest($user);

        $email = (new TemplatedEmail())
            ->from(new Address('testtt@gmail.com', 'Delete Account'))
            ->to($user->getEmail())
            ->subject('Confirm your account deletion')
            ->htmlTemplate('@emails/delete_account.html.twig')
            ->context(['deleteToken' => $token]);

        $mailer->send($email);

        return $this->redirectToRoute('app_check_email_user');
    }

    #[Route('/check-email', name: 'app_check_email_user')]
    public function checkEmail(): Response
    {
        // Twoja logika
        return $this->render('@pages/user_check_email.html.twig');
    }

    #[Route('/delete-confirm/{token}', name: 'app_delete_confirm')]
    public function deleteAccountConfirmation(Request $request, TokenStorageInterface $tokenStorage, SessionInterface $session, string $token = null): Response
    {
        // Jeśli token jest w URL, przechowujemy go w sesji i przekierowujemy na tę samą trasę
        if ($token) {
            $session->set('delete_account_token', $token);
            return $this->redirectToRoute('app_delete_confirm');
        }

        // Pobierz token z sesji
        $token = $session->get('delete_account_token');
        if (!$token) {
            throw $this->createNotFoundException('Token not found in session');
        }

        $deleteRequest = $this->deleteAccountService->validateDeleteAccountToken($token);
        if (!$deleteRequest || $deleteRequest->isExpired()) {
            $session->remove('delete_account_token');  // Czyść token z sesji
            throw $this->createNotFoundException('Token is invalid or expired');
        }

        $form = $this->createForm(DeleteAccountConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Potwierdzenie i usunięcie konta
            $this->deleteAccountService->removeAccount($deleteRequest->getUser());

            // Czyszczenie sesji i wylogowywanie użytkownika
            $tokenStorage->setToken(null);
            $session->invalidate();

            // Możesz przekierować do strony potwierdzenia lub strony głównej
            return $this->redirectToRoute('homepage');
        }

        return $this->render('@pages/user_delete_confirm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
