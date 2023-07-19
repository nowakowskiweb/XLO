<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class ImageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $image = new Image();
            $image->setName($faker->word);
            $image->setOriginalName($faker->word);

            $manager->persist($image);
        }


        $manager->flush();
    }
}
