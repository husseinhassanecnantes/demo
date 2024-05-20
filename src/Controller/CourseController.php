<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cours')]
class CourseController extends AbstractController
{
    #[Route('/', name: 'course_list', methods: ['GET'])]
    public function list(CourseRepository $courseRepository): Response
    {
       // $courses = $courseRepository->findBy(['published' => true], ['name' => 'ASC'], 25);
        $duration = 20;
        $courses = $courseRepository->findLastCourses($duration);

        return $this->render('course/list.html.twig', [
            "courses" => $courses,
        ]);
    }

    #[Route('/{id}', name: 'course_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id, CourseRepository $courseRepository): Response
    {
        $course = $courseRepository->find($id);
        return $this->render('course/show.html.twig',['course' => $course]);
    }

    #[Route('/creer', name: 'course_create', methods: ['GET','POST'])]
    public function create(Request $request): Response
    {
        // todo: traiter le formulaire d'ajout de cours
        return $this->render('course/create.html.twig', [
            // todo : passe le formulaire à twig
        ]);
    }

    #[Route('/{id}/modifier', name: 'course_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(int $id): Response
    {
        // todo: traiter le formulaire de modification de cours
        return $this->render('course/edit.html.twig', [
            // todo : passe le formulaire à twig
        ]);
    }

    #[Route('/demo')]
    public function demo(EntityManagerInterface $em): Response
    {
        $course = new Course();
        $course->setName('Symfony');
        $course->setContent('pas de content');
        $course->setDuration(10);
        $course->setDateCreated(new \DateTimeImmutable());

        dump($course);
        $em->persist($course);
        $em->flush();
        dump($course);


        return $this->render('course/edit.html.twig', [
            // todo : passe le formulaire à twig
        ]);
    }
}
