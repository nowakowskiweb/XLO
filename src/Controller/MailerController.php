<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class MailerController extends AbstractController
{
    #[Route('/registrationn', name: 'app_registration')]
    public function index(MailerInterface $mailer, VerifyEmailHelperInterface $verifyEmailHelper): Response
    {
        $signatureComponents = $verifyEmailHelper->generateSignature(
            'app_verify_email',
            '1',
            'test@gmail.com',
            ['id' => '1']
        );

        $email = (new Email())
            ->from('testtt@gmail.com')
            ->to('nowakowski@gmail.com')
            ->subject('Welcome typie')
            ->text('Test: ' . $signatureComponents->getSignedUrl());

        $mailer->send($email);

        return $this->render('registration/index.html.twig');
    }
}
