<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route("/", name: "main_home", methods: ["GET"])]
    public function home() : Response
    {
        return new Response("<h1>Hello world!</h1>");
    }
}