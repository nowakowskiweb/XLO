<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        return $this->render('homepage/index.html.twig');
    }

    #[Route('/test', name: 'app_new')]
    #[IsGranted('ROLE_USER')]
    public function new(): Response
    {
        return new Response($this->getUser()->getEmail());
        $this->denyAccessUnlessGranted('ROLE_USER');

        return new Response('dupa');
    }


    #[Route('/admin', name: 'app_admin')]
    public function newAdmin(): Response
    {
        return new Response('to jest HomePageController newAdmin()');
    }
}
