<?php

namespace App\DataFixtures;

use App\Entity\Announcement;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    private $fixturesImageDirectory;
    private $imagesForUsers = 5;
    private $imagesExtension = '.jpg';

    private $imageNames = ['pic-1','pic-2','pic-3','pic-4','pic-5','pic-6','pic-7','pic-8','pic-9','pic-10','pic-11','pic-12','pic-13','pic-14','pic-15','pic-16','pic-17','pic-18','pic-19','pic-20','pic-21','pic-22','pic-23','pic-24'];

    public function __construct(string $fixturesImageDirectory) {
        $this->fixturesImageDirectory = $fixturesImageDirectory;
        $this->faker = Factory::create('pl_PL');
    }

    public function load(ObjectManager $manager): void
    {
        $announcements = $manager->getRepository(Announcement::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

        // Przypisujemy obrazy do użytkowników
        foreach ($users as $index => $user) {
            if ($index < $this->imagesForUsers) {
                $image = new Image();
                $originalName = $this->imageNames[$index] . $this->imagesExtension;
                $image->setName($originalName);
                $image->setOriginalName($originalName);
                $image->setPath($this->fixturesImageDirectory);
                $user->setAvatar($image);
                $manager->persist($image);
            }
        }

        $imagesForAnnouncements = array_slice($this->imageNames, $this->imagesForUsers); // Weź tylko obrazy dla ogłoszeń

        $counter = 0;
        while($counter < 3) {
            foreach ($announcements as $announcement) {
                $image = new Image();
                $randomImage = $this->faker->randomElement($imagesForAnnouncements);
                $originalName = $randomImage . $this->imagesExtension; // we always take the first element
                $image->setName($originalName);
                $image->setOriginalName($originalName);
                $image->setPath($this->fixturesImageDirectory);
                $image->setAnnouncement($announcement);
                $manager->persist($image);
            }
            $counter++;
        }

        $manager->flush();
    }

    /**
     * @return list<class-string<FixtureInterface>>
     */
    public function getDependencies(): array
    {
        return [AnnouncementFixtures::class, UserFixtures::class];
    }
}
