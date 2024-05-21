<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
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
      //  $duration = 20;
      //  $courses = $courseRepository->findLastCourses($duration);
        $courses = $courseRepository->findAll();
        return $this->render('course/list.html.twig', [
            "courses" => $courses,
        ]);
    }

    #[Route('/{id}', name: 'course_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id, CourseRepository $courseRepository): Response
    {
        // pour que l'utilisateur ne voit pas les livres qui ne sont pas encore publiés.
        $course = $courseRepository->findOneBy(['published' => true, 'id' => $id]);
        if(!$course)
        {
            throw $this->createNotFoundException('Le livre n\'existe pas');
        }
        return $this->render('course/show.html.twig',['course' => $course]);
    }

    #[Route('/creer', name: 'course_create', methods: ['GET','POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $course = new Course();
        $formCourse = $this->createForm(CourseType::class,$course);

        $formCourse->handleRequest($request);

        if($formCourse->isSubmitted())
        {
            $em->persist($course);
            $em->flush();

            $this->addFlash('success', 'Le cours a été bien créé!');
            return $this->redirectToRoute('course_show',['id' => $course->getId()]);
        }
        return $this->render('course/create.html.twig', [
            'formCourse' => $formCourse
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
