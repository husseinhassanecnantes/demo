<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{


    public function __construct(private readonly  UserPasswordHasherInterface $userPasswordHasher)
    {}

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        $user = new User();
        $user->setEmail('admin@eni.fr');
        $user->setLastname("adminet");
        $user->setFirstname("adminou");
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, '123456'));

        $manager->persist($user);

        $user = new User();
        $user->setEmail('planner@eni.fr');
        $user->setLastname("plannernat");
        $user->setFirstname("plannerou");
        $user->setRoles(['ROLE_PLANNER']);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, '123456'));

        $manager->persist($user);

        for ($i = 1; $i<=20; $i++)
        {
            $user = new User();
            $user->setEmail("user$i@eni.fr");
            $user->setLastname($faker->lastName);
            $user->setFirstname($faker->firstName);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, '123456'));
            $this->addReference('user'.$i, $user );
            $manager->persist($user);
        }

        $manager->flush();
    }
}
