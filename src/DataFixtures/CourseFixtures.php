<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CourseFixtures extends Fixture implements DependentFixtureInterface
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

            $course->setCategory($this->getReference('cat'.$faker->numberBetween(1,10)));
            $course->setUser($this->getReference('user'.$faker->numberBetween(1,20)));
            $nb = $faker->numberBetween(1,5);
            for($t = 1; $t <= $nb; $t++)
            {
                $course->addTrainer($this->getReference('trainer'.$faker->numberBetween(1,10)));
            }

            $manager->persist($course);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [CategoryFixtures::class, TrainerFixtures::class, UserFixtures::class];
    }
}
