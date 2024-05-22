<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i = 1; $i <= 10 ; $i++)
        {
            $categorie = new Category();
            $categorie->setName($faker->slug);
            $dateCreated = $faker->dateTimeBetween('-10 years','-2 years');
            $categorie->setDateCreated(\DateTimeImmutable::createFromMutable($dateCreated));
            $dateModified = $faker->optional()->dateTimeBetween($dateCreated);
            if($dateModified)
            {
                $categorie->setDateUpdate(\DateTimeImmutable::createFromMutable($dateModified));
            }
            $this->addReference('cat'.$i, $categorie );
            $manager->persist($categorie);
        }
        $manager->flush();
    }
}
