<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'last_username' => $authenticationUtils->getLastUsername()
        ]);

    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        throw new Expection('test test');
    }
    #[Route('/testuje-dwa', name: 'app_test-dwa')]
    public function testDwa(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_COMMENT_ADMIN');

        throw new Expection('Testuje dwa');
    }

    #[Route('/authenticate/2fa/enable', name: 'app_2fa_enable')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function enable2fa(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user->isEmailAuthEnabled()) {
            // Ustawianie konfiguracji 2FA dla użytkownika
            $user->setEmailAuthEnabled(true);

            // Wygenerowanie i zapisanie nowego kodu dla autoryzacji e-mailem
//            $emailAuthCodeManager->generateAndSend($user);

            // Zapisanie zmian w bazie danych
            $entityManager->flush();
        }

        return new Response('dupa');
    }

    #[Route('/authenticate/2fa/disable', name: 'app_2fa_disable')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function disable2fa(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($user->isEmailAuthEnabled()) {
            // Ustawianie konfiguracji 2FA dla użytkownika
            $user->setEmailAuthEnabled(false);

            $entityManager->flush();
        }

        return new Response('dupa2');
    }
}
