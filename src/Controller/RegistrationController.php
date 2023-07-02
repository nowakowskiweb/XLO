<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\User;
use App\Services\ImageService;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(MailerInterface $mailer, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, VerifyEmailHelperInterface $verifyEmailHelper, ImageService $imageService): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();

            if ($form->get('firstName')->getData()) {
                $user->setFirstName($form->get('firstName')->getData());
            }

            if ($form->get('lastName')->getData()) {
                $user->setLastName($form->get('lastName')->getData());
            }

            $user->setEmail($form->get('email')->getData());
            $user->setLogin($form->get('login')->getData());

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            if ($form->get('avatar')->getData()) {
                $avatar = $form->get('avatar')->getData();

                $image = $imageService->add($avatar, 'avatars');
                $user->setAvatar($image);
            }

            $entityManager->persist($user);
            $entityManager->flush();
//
//            $signatureComponents = $verifyEmailHelper->generateSignature(
//                'app_verify_email',
//                $user->getId(),
//                $user->getEmail(),
//                ['id' => $user->getId()]
//            );
//
//            $email = (new Email())
//                ->from('testtt@gmail.com')
//                ->to('nowakowski@gmail.com')
//                ->subject('Welcome typie')
//                ->text('Test: ' . $signatureComponents->getSignedUrl());
//
//            $mailer->send($email);

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('registration/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, VerifyEmailHelperInterface $verifyEmailHelper, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $user = $userRepository->find($request->query->get('id'));
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

        $user->setVerified(true);
        $entityManager->flush();

        return new Response('dudddupa');
    }

    #[Route('/verify/resend', name: 'app_verify_resend_email')]
    public function resendVerifyEmail(Request $request)
    {
        return new Response('dudddupa');
    }
}
