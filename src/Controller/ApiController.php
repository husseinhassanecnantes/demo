<?php

namespace App\Controller;

use App\Model\Region;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiController extends AbstractController
{
    #[Route('/region', name: 'api_region')]
    public function index(SerializerInterface $serializer, HttpClientInterface $httpClient): Response
    {
       // $content = file_get_contents('https://geo.api.gouv.fr/regions');
        $content = $httpClient->request('GET', 'https://geo.api.gouv.fr/regions')->getContent();

        /*
        $regions_array = $serializer->decode($content, 'json');
        $regions = $serializer->denormalize($regions_array, Region::class . '[]');
         */

        $regions = $serializer->deserialize($content, Region::class . '[]', 'json');

        return $this->render('api/region.html.twig', [
           'regions' => $regions
        ]);
    }
}
