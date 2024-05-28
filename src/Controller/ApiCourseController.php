<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Course;
use App\Entity\Trainer;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiCourseController extends AbstractController
{
    #[Route('/api/course', name: 'api_course_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $data = $request->getContent();

        $course = $serializer->deserialize($data, Course::class, 'json');

        $errors = $validator->validate($course);

        $dataTab = $request->toArray();

        // Set the Category
        $category = $em->getRepository(Category::class)->find($dataTab['category']['id']);
        if (!$category) {
            return new JsonResponse(['error' => 'Invalid category ID'], Response::HTTP_BAD_REQUEST);
        }
        $course->setCategory($category);

        // Set the User
        $user = $em->getRepository(User::class)->find($dataTab['user']['id']);
        if (!$user) {
            return new JsonResponse(['error' => 'Invalid user ID'], Response::HTTP_BAD_REQUEST);
        }
        $course->setUser($user);

        if(count($errors)> 0)
        {
            $errorJson = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorJson, Response::HTTP_BAD_REQUEST, [], true);
        }
        $em->persist($course);
        $em->flush();

        return $this->json($course, Response::HTTP_CREATED, [], ['groups' => 'getCourses']);
    }

    #[Route('/api/course/{id}/trainers', name: 'course_add_trainers', methods: ['POST'])]
    public function addTrainers(
        int $id,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $data = $request->getContent();
        $dataTab = json_decode($data, true);

        $course = $em->getRepository(Course::class)->find($id);
        if (!$course) {
            return new JsonResponse(['error' => 'Invalid course ID'], Response::HTTP_BAD_REQUEST);
        }

        if (isset($dataTab['trainers']) && is_array($dataTab['trainers'])) {
            foreach ($dataTab['trainers'] as $trainerData) {
                $trainer = $em->getRepository(Trainer::class)->find($trainerData['id']);
                if ($trainer) {
                    $course->addTrainer($trainer);
                } else {
                    return new JsonResponse(['error' => "Invalid trainer ID: {$trainerData['id']}"], Response::HTTP_BAD_REQUEST);
                }
            }
        } else {
            return new JsonResponse(['error' => 'No trainers data provided or invalid format'], Response::HTTP_BAD_REQUEST);
        }

        $em->persist($course);
        $em->flush();

        return $this->json($course, Response::HTTP_OK, [], ['groups' => 'getCourses']);
    }

}
