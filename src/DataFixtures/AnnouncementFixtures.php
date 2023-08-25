<?php

namespace App\DataFixtures;

use App\Entity\Announcement;
use App\Entity\Category;
use App\Entity\Image;
use App\Entity\User;
use App\Form\Type\ConditionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class AnnouncementFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('pl_PL');

        $users = $manager->getRepository(User::class)->findAll();
        $categories = $manager->getRepository(Category::class)->findAll();

        for ($i = 0; $i < 22; $i++) {
            $announcement = new Announcement();

            $announcement->setTitle($faker->realText(50));

            $announcement->setDescription($faker->realText(150));
            $announcement->setPrice($faker->numberBetween( 1000, 1000000));
            $announcement->setPublished(true);
            $announcement->setVoivodeship($faker->city);
            $announcement->setCity($faker->city);
            $announcement->setConditionType($faker->randomElement(ConditionType::getConditionKeys()));

            $randomUser = $faker->randomElement($users);
            $randomUserFavorite = $faker->randomElement($users);
            $announcement->setUser($randomUser);
            $announcement->addFavoritedBy($randomUserFavorite);
            $announcement->setCategory($faker->randomElement($categories));

            $manager->persist($announcement);
        }

        $manager->flush();
    }

    /**
     * @return list<class-string<FixtureInterface>>
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, UserFixtures::class];
    }
}
