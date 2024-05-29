<?php

namespace App\Util;

use App\Repository\CourseRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class TrainingService
{

    public function __construct(private readonly CourseRepository $courseRepository)
    {
    }

    public function getCost(int $id)
    {
       $course = $this->courseRepository->find($id);

        if(!$course)
        {
            throw new \Exception('course not found');
        }

        $cost = 850 * $course->getDuration();
        if($course->getDuration() < 5)
        {
            $cost = 1000 * $course->getDuration();
        } elseif ($course->getDuration() < 10)
        {
            $cost = 950 * $course->getDuration();
        }

        return $cost;
    }
}