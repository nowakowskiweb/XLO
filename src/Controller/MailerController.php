<?php

namespace App\Controller;

use App\Form\UserEditProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class MailerController extends AbstractController
{
    private function sendEmail(MailerInterface $mailer, VerifyEmailHelperInterface $verifyEmailHelper, string $to, string $subject): void
    {
        $signatureComponents = $verifyEmailHelper->generateSignature(
            'app_verify_email',
            '1',
            $to,
            ['id' => '1']
        );

        $email = (new Email())
            ->from('testtt@gmail.com')
            ->to($to)
            ->subject($subject)
            ->text('Test: ' . $signatureComponents->getSignedUrl());

        $mailer->send($email);
    }

    #[Route('/registration', name: 'mailer_registration')]
    public function registration(MailerInterface $mailer, VerifyEmailHelperInterface $verifyEmailHelper): Response
    {
        $this->sendEmail($mailer, $verifyEmailHelper, 'nowakowski@gmail.com', 'Welcome typie');
        return $this->render('registration/index.html.twig');
    }

    #[Route('/reset-password-request', name: 'mailer_reset_password')]
    public function resetPassword(MailerInterface $mailer, VerifyEmailHelperInterface $verifyEmailHelper): Response
    {
        $this->sendEmail($mailer, $verifyEmailHelper, 'nowakowski@gmail.com', 'Welcome typie');
        return $this->render('registration/index.html.twig');
    }

    #[Route('/change-email-request', name: 'mailer_change_email')]
    public function changeEmail(MailerInterface $mailer, VerifyEmailHelperInterface $verifyEmailHelper): Response
    {
        $this->sendEmail($mailer, $verifyEmailHelper, 'nowakowski@gmail.com', 'Welcome typie');
        return $this->render('registration/index.html.twig');
    }

    #[Route('/delete-account-request', name: 'mailer_delete_account')]
    public function deleteAccount(MailerInterface $mailer, VerifyEmailHelperInterface $verifyEmailHelper): Response
    {
        $this->sendEmail($mailer, $verifyEmailHelper, 'nowakowski@gmail.com', 'Welcome typie');
        return $this->render('registration/index.html.twig');
    }
}
