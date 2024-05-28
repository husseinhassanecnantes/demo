<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Model\CategoryDTO;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiCategoryController extends AbstractController
{
    #[Route('/api/categories', name: 'api_category_list', methods: ['GET'])]
    public function list(CategoryRepository $categoryRepository, SerializerInterface $serializer): JsonResponse
    {
        $categories = $categoryRepository->findBy([], ['name' => 'ASC']);

      //  $result = $serializer->serialize($categories, 'json', ['groups' => 'getCategoriesFull']);
      //  return new JsonResponse($result, Response::HTTP_OK, [], true);

        return $this->json($categories, Response::HTTP_OK, [], ['groups' => 'getCategoriesFull']);
    }

    #[Route('/api/categories/{id}', name: 'api_category_read', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function read(?Category $category, SerializerInterface $serializer): JsonResponse
    {
        if(!$category)
        {
            return new JsonResponse(null, JsonResponse::HTTP_NOT_FOUND);
        }
        $result = $serializer->serialize($category, 'json', ['groups' => 'getCategories']);
        return new JsonResponse($result, Response::HTTP_OK, [], true);
    }

    #[Route('/api/categories', name: 'api_category_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CategoryDTO $categoryDTO,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): JsonResponse
    {
        // méthode 3 en utilisant un DTO
        $category = Category::createfromDTO($categoryDTO);
        $entityManager->persist($category);
        $entityManager->flush();

        return $this->json($category, JsonResponse::HTTP_CREATED, [], []);

        /*
         * // méthode 1 en utilisant validator
        $data = $request->getContent();
        $category = $serializer->deserialize($data, Category::class, 'json');

        $errors = $validator->validate($category);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = [
                    'field' => $error->getPropertyPath(),
                    'message' => $error->getMessage()
                ];
            }
            return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($category);
        $entityManager->flush();
         */

        /*
        * // méthode 2 en utilisant un formulaire
        $data = json_decode($request->getContent(), true);
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category, ['csrf_protection' => false]);
        $form->submit($data, true);

        if (!$form->isValid()) {
            $errorMessages = [];
            foreach ($form->getErrors(true) as $error) {
                $errorMessages[] = [
                    'field' => $error->getOrigin()->getName(),
                    'message' => $error->getMessage()
                ];
            }
            return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($category);
        $entityManager->flush();
         *
         */
        return $this->json($category, JsonResponse::HTTP_CREATED, ["Location" => $this->generateUrl(
            'api_category_read',
            ['id' => $category->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        )], ['groups' => 'getCategories']);
    }

    #[Route('/api/categories/{id}', name: 'api_category_update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function update(?Category $category, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        if(!$category)
        {
            return new JsonResponse(null, JsonResponse::HTTP_NOT_FOUND);
        }
        /*
              $data = $request->getContent();

              $category = $serializer->deserialize($data, Category::class, 'json', [
                  AbstractNormalizer::OBJECT_TO_POPULATE => $category
              ]);

              $category->setDateUpdate(new \DateTimeImmutable());



              $errors = $validator->validate($category);
              if (count($errors) > 0) {
                  $errorMessages = [];
                  foreach ($errors as $error) {
                      $errorMessages[] = [
                          'field' => $error->getPropertyPath(),
                          'message' => $error->getMessage()
                      ];
                  }
                  return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
              }
           */

        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(CategoryType::class, $category, ['csrf_protection' => false]);
        $form->submit($data, true);

        if (!$form->isValid()) {
            $errorMessages = [];
            foreach ($form->getErrors(true) as $error) {
                $errorMessages[] = [
                    'field' => $error->getOrigin()->getName(),
                    'message' => $error->getMessage()
                ];
            }
            return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($category);
        $entityManager->flush();

        return $this->json($category, JsonResponse::HTTP_OK, [], ['groups' => 'getCategories']);
    }

    #[Route('/api/categories/{id}', name: 'api_category_patch', requirements: ['id' => '\d+'], methods: ['PATCH'])]
    public function patch(?Category $category, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        if (!$category) {
            return new JsonResponse(null, JsonResponse::HTTP_NOT_FOUND);
        }
        /*
            $data = $request->getContent();

            $serializer->deserialize($data, Category::class, 'json', [
                AbstractNormalizer::OBJECT_TO_POPULATE => $category,
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
            ]);
            $category->setDateUpdate(new \DateTimeImmutable());


            $errors = $validator->validate($category);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = [
                        'field' => $error->getPropertyPath(),
                        'message' => $error->getMessage()
                    ];
                }
                return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
            }
         */

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(CategoryType::class, $category, ['csrf_protection' => false]);
        $form->submit($data, false); // false to allow partial updates

        if (!$form->isValid()) {
            $errorMessages = [];
            foreach ($form->getErrors(true) as $error) {
                $errorMessages[] = [
                    'field' => $error->getOrigin()->getName(),
                    'message' => $error->getMessage()
                ];
            }
            return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        $category->setDateUpdate(new \DateTimeImmutable());

        $entityManager->persist($category);
        $entityManager->flush();

        return $this->json($category, JsonResponse::HTTP_OK, [], ['groups' => 'getCategories']);
    }

    #[Route('/api/categories/{id}', name: 'api_category_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(?Category $category, EntityManagerInterface $entityManager): JsonResponse
    {
        if(!$category)
        {
            return new JsonResponse(null, JsonResponse::HTTP_NOT_FOUND);
        }

        $entityManager->remove($category);
        $entityManager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
