<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Trainer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TrainerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i = 1; $i <= 10 ; $i++)
        {
            $trainer = new Trainer();
            $trainer->setFirstname($faker->firstName);
            $trainer->setLastname($faker->lastName);
            $dateCreated = $faker->dateTimeBetween('-10 years','-2 years');
            $trainer->setDateCreated(\DateTimeImmutable::createFromMutable($dateCreated));
            $dateModified = $faker->optional()->dateTimeBetween($dateCreated);
            if($dateModified)
            {
                $trainer->setDateUpdated(\DateTimeImmutable::createFromMutable($dateModified));
            }
            $this->addReference('trainer'.$i, $trainer );
            $manager->persist($trainer);
        }
        $manager->flush();
    }
}
