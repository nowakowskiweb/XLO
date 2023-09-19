<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Form\AddAnnouncementType;
use App\Form\Type\ConditionType;
use App\Form\Type\SortingType;
use App\Repository\AnnouncementRepository;
use App\Repository\CategoryRepository;
use App\Services\ImageService;
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

        return $this->render('@pages/announcements_index.html.twig', [
            'announcements' => $announcements,
            'categories' => $categories,
            'conditions' => $conditions,
            'sorting' => $sorting,
            'paginationFilters' => $output
        ]);
    }

    #[Route('/announcements/add', name: 'announcement_add')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function store(Request $request, EntityManagerInterface $entityManager, ImageService $imageService): Response
    {
        $form = $this->createForm(AddAnnouncementType::class, null, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $announcement = new Announcement();

            $announcement->setTitle($form->get('title')->getData());
            $announcement->setDescription($form->get('description')->getData());
            $announcement->setPrice($form->get('price')->getData());
            $announcement->setVoivodeship($form->get('voivodeship')->getData());
            $announcement->setCity($form->get('city')->getData());
            $announcement->setConditionType($form->get('conditionType')->getData());
            $announcement->setCategory($form->get('category')->getData());
            $announcement->setUser($this->getUser());
            $images = $form->get('images')->getData();

            foreach ($images as $imageEntity) {
                $uploadedFile = $imageEntity->getFile();
                if ($uploadedFile) {
                    $image = $imageService->add($uploadedFile, 'announcements');
                    $announcement->addImage($image);
                }
            }

            $entityManager->persist($announcement);
            $entityManager->flush();

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('@pages/announcements_add.html.twig', [
            'announcementForm' => $form->createView(),
        ]);
    }


    #[Route('/announcements/{id}', methods: ['GET'], name: 'announcement_show')]
    public function show($id): Response
    {
        $announcement = $this->announcementRepository->find($id);

        return $this->render('@pages/announcements_show.html.twig', [
            'announcement' => $announcement
        ]);
    }
}
