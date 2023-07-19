<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName($faker->word);
            $category->setDescription($faker->sentence);

            $image = new Image();
            $image->setName($faker->imageUrl());
            $image->setOriginalName($faker->word . '.jpg');
            $category->setImage($image);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
