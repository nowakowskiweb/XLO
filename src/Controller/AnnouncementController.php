<?php

namespace App\Controller;

use App\Repository\AnnouncementRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
class AnnouncementController extends BaseController
{
    private $entityManager;
    private $announcementRepository;

    public function __construct(EntityManagerInterface $entityManager,AnnouncementRepository $announcementRepository)
    {
        $this->entityManager = $entityManager;
        $this->announcementRepository = $announcementRepository;
    }


    #[Route('/announcements', name: 'announcement_index')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
//        $announcements = $this->announcementRepository->findPublished(true);
//
//        $pagination = $paginator->paginate(
//            $this->announcementRepository->paginatorQuery(true),
//            $request->query->get('page',1),
//            10
//        );

        // Pass the data to the view
//        return $this->render('@pages/announcements-list.html.twig', [
//            'pagination' => $pagination,
//        ]);
        $page = $request->query->getInt('page', 1);

        $announcements = $this->announcementRepository->findsAnnouncementsPaginated($page, 10);

        return $this->render('@pages/announcements-list.html.twig', [
            'announcements' => $announcements,
        ]);
    }

    #[Route('/announcements/add', name: 'announcement_add')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function store(): Response
    {
        return $this->render('@pages/announcement.html.twig', [
            'controller_name' => 'AnnouncementController',
        ]);
    }

    #[Route('/announcements/{id}', methods: ['GET'], name: 'announcement_show')]
    public function show($id): Response
    {
        $announcement = $this->announcementRepository->find($id);

        return $this->render('@pages/announcements-show.html.twig', [
            'announcement' => $announcement
        ]);
    }
}
