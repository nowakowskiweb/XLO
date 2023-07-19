<?php

namespace App\DataFixtures;

use App\Entity\Announcement;
use App\Entity\Category;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class AnnouncementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 50; $i++) {
            $announcement = new Announcement();
            $announcement->setTitle($faker->sentence);
            $announcement->setDescription($faker->paragraph);
            $announcement->setPrice($faker->randomFloat(2, 10, 10000));
            $announcement->setVoivodeship($faker->city);
            $announcement->setCity($faker->city);
            $announcement->setConditionType($faker->randomElement(['new', 'used', 'refurbished']));

            $manager->persist($announcement);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
            ImageFixtures::class,
        ];
    }
}
