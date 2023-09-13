<?php

namespace App\Decorator;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;

class TokenHandlerDecorator implements ResetPasswordHelperInterface
{
    public function __construct(
        private ResetPasswordHelperInterface $decorated,
        private EntityManagerInterface $entityManager)
    {
    }

    public function generateResetToken(object $user): ResetPasswordToken
    {
        return $this->decorated->generateResetToken($user);
    }

    public function generateFakeResetToken(): ResetPasswordToken
    {
        return $this->decorated->generateFakeResetToken();
    }

    public function validateTokenAndFetchUser(string $fullToken): object
    {
        return $this->decorated->validateTokenAndFetchUser($fullToken);
    }

    public function removeResetRequest(string $fullToken): void
    {
        $this->decorated->removeResetRequest($fullToken);
    }

    public function getTokenLifetime(): int
    {
        return $this->decorated->getTokenLifetime();
    }

    public function processSendingEmail(string $emailFormData, MailerInterface $mailer, TranslatorInterface $translator): RedirectResponse
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        // Do not reveal whether a user account was found or not.
        if (!$user) {
            return $this->redirectToRoute('app_check_email');
        }

        try {
            $resetToken = $this->decorated->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            // If you want to tell the user why a reset email was not sent, uncomment
            // the lines below and change the redirect to 'app_forgot_password_request'.
            // Caution: This may reveal if a user is registered or not.
            //
            // $this->addFlash('reset_password_error', sprintf(
            //     '%s - %s',
            //     $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_HANDLE, [], 'ResetPasswordBundle'),
            //     $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            // ));

//            return $this->redirectToRoute('app_check_email');
        }

        $email = (new TemplatedEmail())
            ->from(new Address('testtt@gmail.com', 'Change Password'))
            ->to($user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ])
        ;

        $mailer->send($email);

        // Store the token object in session for retrieval in check-email route.
        $this->decorated->setTokenObjectInSession($resetToken);

        return $this->redirectToRoute('app_check_email');
    }
}