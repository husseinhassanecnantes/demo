<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/cours')]
class CourseController extends AbstractController
{
    #[Route('/', name: 'course_list', methods: ['GET'])]
    public function list(CourseRepository $courseRepository): Response
    {
        //$courses = $courseRepository->findBy(['published' => true], ['dateCreated' => 'DESC'], 25);
        $duration = 2;
        $courses = $courseRepository->findLastCourses($duration);
       // $courses = $courseRepository->findAll();
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
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $course = new Course();
        $formCourse = $this->createForm(CourseType::class,$course);

        $formCourse->handleRequest($request);

        if($formCourse->isSubmitted() && $formCourse->isValid())
        {
            $courseFile = $formCourse->get('file')->getData();

            if ($courseFile) {
                $originalFilename = pathinfo($courseFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $courseFile->guessExtension();

                try {
                    $courseFile->move($this->getParameter('files_directory'), $newFilename);
                    $course->setFilename($newFilename);
                } catch (FileException $e) {
                    dd($e);
                }
            }

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
    public function edit(Course $course, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $formCourse = $this->createForm(CourseType::class, $course);

        $formCourse->handleRequest($request);

        if($formCourse->isSubmitted() && $formCourse->isValid())
        {

            if($formCourse->has('deleteCb') && $formCourse->get('deleteCb')->getData())
            {
                unlink($this->getParameter('files_directory') . '/' . $course->getFilename());
                $course->setFilename(null);
            }

            $courseFile = $formCourse->get('file')->getData();

            if ($courseFile && $course->getFilename() === null)
            {
                $originalFilename = pathinfo($courseFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $courseFile->guessExtension();

                try {
                    $courseFile->move($this->getParameter('files_directory'), $newFilename);
                    $course->setFilename($newFilename);
                } catch (FileException $e) {
                    dd($e);
                }
            }

            $course->setDateModified(new \DateTimeImmutable());
            $em->persist($course);
            $em->flush();

            $this->addFlash('success', 'Le cours a été bien modifié!');
            return $this->redirectToRoute('course_show',['id' => $course->getId()]);
        }
        return $this->render('course/edit.html.twig', [
            'course' => $course,
            'formCourse' => $formCourse
        ]);
    }

    #[Route('/{id}/delete/{token}', name: 'course_delete', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function delete(Course $course, string $token, EntityManagerInterface $em): Response
    {
        $verifiedToken = $this->isCsrfTokenValid('delete-token-'.$course->getId(), $token);

        if($verifiedToken)
        {
            $em->remove($course);
            $em->flush();
            $this->addFlash('success', 'Cours a été supprimé!');
            return $this->redirectToRoute('course_list');
        }

        $this->addFlash('danger', 'La suppression du cours a été échouée');
        return $this->redirectToRoute('course_show', ['id' => $course->getId()]);
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

    #[Route('/{id}/formateurs', name: 'course_trainers', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[IsGranted('ROLE_PLANNER')]
    public function trainer(Course $course): Response
    {
        $trainers = $course->getTrainers();
        return $this->render('course/trainers.html.twig', ['trainers' => $trainers, 'course' => $course]);
    }
}
