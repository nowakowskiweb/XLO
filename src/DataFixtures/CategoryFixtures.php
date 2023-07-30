<?php

namespace App\DataFixtures;

use App\Entity\Announcement;
use App\Entity\Category;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('pl_PL');

        for ($i = 0; $i < 30; $i++) {
            $mainCategory = new Category();
            $mainCategory->setName($faker->word);
            $mainCategory->setDescription($faker->realText(50));

            if ($faker->randomElement([true, false])) {
                $subcategoriesCount = $faker->numberBetween(0, 5);
                for ($j = 0; $j < $subcategoriesCount; $j++) {
                    $subcategory = new Category();
                    $subcategory->setName($faker->word);
                    $subcategory->setDescription($faker->realText(50));
                    $subcategory->setParent($mainCategory);
                    $manager->persist($subcategory);
                }
            }

            $manager->persist($mainCategory);
        }


        $manager->flush();
    }
}
