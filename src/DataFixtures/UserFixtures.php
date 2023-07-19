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

        for ($i = 0; $i < 30; $i++) {
            $user = new User();

            $user->setEmail($faker->unique()->email); // Generate a unique email for each user
            $user->setLogin($this->generateUniqueLogin($faker, $i));
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setVerified(false);
            $user->setRoles(['ROLE_USER']);

            $plainPassword = 'test' . $i;
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $this->addReference(User::class . '_' . $i, $user);
        }

        $manager->flush();
    }

    private function generateUniqueLogin($faker, $index): string
    {
        $login = $faker->userName;
        // Adding a suffix to make it unique, in this case, using the index
        return $login . $index;
    }
}
