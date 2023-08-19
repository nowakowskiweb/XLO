<?php

namespace App\Controller;

use App\DataFixtures\CategoryFixtures;
use App\Form\Type\CategoriesType;
use App\Form\Type\ConditionType;
use App\Form\Type\SortingType;
use App\Repository\AnnouncementRepository;
use App\Repository\CategoryRepository;
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
    private $categoryRepository;


    public function __construct(EntityManagerInterface $entityManager,AnnouncementRepository $announcementRepository,CategoryRepository $categoryRepository)
    {
        $this->entityManager = $entityManager;
        $this->announcementRepository = $announcementRepository;
        $this->categoryRepository = $categoryRepository;
    }

    #[Route('/announcements', name: 'announcement_index')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);

        $announcements = $this->announcementRepository->findsAnnouncementsPaginated($page, 10, $request);

        $sorting = SortingType::getSorting();
        $conditions = ConditionType::getConditions();
        $categories = $this->categoryRepository->findAllNames();
        $output = [];

        foreach ($announcements['filters'] as $key => $value) {
            // Jeśli wartość nie jest tablicą, dodajemy ją bezpośrednio do wyniku
            if (!is_array($value)) {
                $output[$key] = $value;
            } else {
                if (count($value) == 1) {
                    // Jeśli tablica ma tylko jeden element, dodajemy tylko klucz
                    $output[$key] = key($value);
                } else {
                    // Jeśli tablica ma więcej niż jeden element, dodajemy wszystkie klucze
                    $output[$key] = array_keys($value);
                }
            }
        }

        return $this->render('@pages/announcements-list.html.twig', [
            'announcements' => $announcements,
            'categories' => $categories,
            'conditions' => $conditions,
            'sorting' => $sorting,
            'test' => $output
        ]);
    }

    #[Route('/announcements/add', name: 'announcement_add')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function store(): Response
    {
        return $this->render('@pages/announcement-show.html.twig', [
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
