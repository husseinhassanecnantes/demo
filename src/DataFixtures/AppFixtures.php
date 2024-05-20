<?php

namespace App\DataFixtures;

use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i = 1; $i < 30 ; $i++)
        {
            $course = new Course();
            $course->setName($faker->sentence);
            $course->setContent($faker->text(600));
            $course->setPublished($faker->boolean(90));
            $dateCreated = $faker->dateTimeBetween('-2 years','now');
            $course->setDateCreated(\DateTimeImmutable::createFromMutable($dateCreated));
            $course->setDuration($faker->numberBetween(1,30));

            $dateModified = $faker->optional()->dateTimeBetween($dateCreated);
            if($dateModified)
            {
                $course->setDateModified(\DateTimeImmutable::createFromMutable($dateModified));
            }

            $manager->persist($course);
        }
        $manager->flush();
    }
}
