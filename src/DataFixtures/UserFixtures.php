<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\Groups;
use Faker\Factory;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('pl_PL');
        // Create admin user
        $admin = new User();
        $admin->setEmail('admin@gmail.com');
        $admin->setLogin('admin');
        $admin->setFirstName('Łukasz');
        $admin->setVerified(true);
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin');
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        for ($i = 0; $i < 10; $i++) {
            $user = new User();

            $user->setEmail($faker->unique()->email); // Generate a unique email for each user
            $user->setLogin($faker->firstName . $i);
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setVerified(false);
            $user->setRoles(['ROLE_USER']);

            $plainPassword = 'test' . $i;
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
