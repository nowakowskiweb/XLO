<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ObjectManager;
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
        $faker = Factory::create();

        $user = new User();
        $user->setEmail('admin@gmail.com');
        $user->setLogin("admin");
        $user->setFirstName('Łukasz');
        $user->setVerified(true);
        $user->setRoles(['ROLE_ADMIN']);

        $plainPassword = 'test';
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);

        $manager->persist($user);

        $user2 = new User();
        $user2->setEmail('user@gmail.com');
        $user2->setLogin("user");
        $user2->setFirstName('Łukasz');
        $user2->setVerified(true);
        $user2->setRoles(['ROLE_USER']);

        $plainPassword = 'test';
        $hashedPassword = $this->passwordHasher->hashPassword($user2, $plainPassword);
        $user2->setPassword($hashedPassword);

        $manager->persist($user2);

        for ($i = 0; $i < 10; $i++) {
            $user = new User();

            $user->setEmail($faker->email);
            $user->setLogin("anita" . $i);
            $user->setFirstName($faker->firstName);
            $user->setVerified(false);

            $plainPassword = 'test' . $i;
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
