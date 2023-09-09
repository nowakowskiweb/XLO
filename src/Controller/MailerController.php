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

    #[Route('/email-change', name: 'mailer_email_change')]
    public function changeEmail(MailerInterface $mailer, VerifyEmailHelperInterface $verifyEmailHelper): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserEditProfileType::class,$user);
        $this->sendEmail($mailer, $verifyEmailHelper, 'nowakowski@gmail.com', 'Zmien email');

        return $this->render('@pages/user-edit.html.twig', [
            'userEditForm' => $form->createView(),
            'user' => $user,
            'isSubmitted' => false
        ]);
    }

    #[Route('/password-change', name: 'mailer_password_change')]
    public function changePassword(MailerInterface $mailer, VerifyEmailHelperInterface $verifyEmailHelper): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserEditProfileType::class,$user);

        $this->sendEmail($mailer, $verifyEmailHelper, 'nowakowski@gmail.com', 'Zmien hasło');
        return $this->render('@pages/user-edit.html.twig', [
            'userEditForm' => $form->createView(),
            'user' => $user,
            'isSubmitted' => false
        ]);
    }

    #[Route('/account-delete', name: 'mailer_account_delete')]
    public function deleteAccount(MailerInterface $mailer, VerifyEmailHelperInterface $verifyEmailHelper): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserEditProfileType::class,$user);


        $this->sendEmail($mailer, $verifyEmailHelper, 'nowakowski@gmail.com', 'Usun konto');
        return $this->render('@pages/user-edit.html.twig', [
            'userEditForm' => $form->createView(),
            'user' => $user,
            'isSubmitted' => false
        ]);
    }
}
