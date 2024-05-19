<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cours')]
class CourseController extends AbstractController
{
    #[Route('/', name: 'course_list', methods: ['GET'])]
    public function list(): Response
    {
        return $this->render('course/list.html.twig', [
        ]);
    }

    #[Route('/{id}', name: 'course_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        return $this->render('course/show.html.twig');
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
    public function edit(): Response
    {
        // todo: traiter le formulaire de modification de cours
        return $this->render('course/edit.html.twig', [
            // todo : passe le formulaire à twig
        ]);
    }
}
