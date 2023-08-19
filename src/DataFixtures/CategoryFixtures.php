<?php

namespace App\DataFixtures;

use App\Entity\Announcement;
use App\Entity\Category;
use App\Entity\Image;
use App\Entity\User;
use App\Form\Type\CategoriesType;
use App\Form\Type\ConditionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = CategoriesType::getCategories();

        foreach ($categories as $category) {
            $newCategory = new Category();
            $newCategory->setName($category['name']);
            $newCategory->setDescription($category['description']);
            $newCategory->setSlug($category['slug']);

            $manager->persist($newCategory);
        }

        $manager->flush();
    }
}
