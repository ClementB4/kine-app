<?php

namespace App\Controller\Api;

use App\Entity\Pathology;
use App\Repository\PathologyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/pathologies')]
final class PathologyController extends BaseApiController
{
    #[Route('', methods: ['GET'])]
    public function index(
        PathologyRepository $pathologyRepository
    ): JsonResponse
    {
        $pathologies = $pathologyRepository->findAll();

        return $this->json(
            $pathologies,
            200,
            [],
            ['groups' => ['pathology.index']]
        );
    }

        #[Route('/{id}', methods: ['GET'])]
    public function show(
        int $id,
        PathologyRepository $pathologyRepository
    ): JsonResponse
    {
        $pathology = $pathologyRepository->find($id);

        if (!$pathology) {
            return $this->notFound('Pathology');
        }

        return $this->json(
            $pathology,
            200,
            [],
            ['groups' => ['pathology.show', 'dates']]
        );
    }

    #[Route('', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $data = $this->parseJson($request);

        $pathology = new Pathology();
        $this->hydratePathology($pathology, $data);

        $errors = $this->validationErrors($pathology, $validator);

        if ($errors) {
            return $this->json(
                ['Error' => $errors],
                400
            );
        }

        $em->persist($pathology);
        $em->flush();
        return $this->json(
            $pathology,
            201,
            [],
            ['groups' => ['pathology.show', 'dates']]
        );
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        int $id,
        PathologyRepository $pathologyRepository,
        EntityManagerInterface $em,
        Request $request,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $data = $this->parseJson($request);

        $pathology = $pathologyRepository->find($id);
        if (!$pathology) {
            return $this->notFound('Pathology');
        } else {
            $this->hydratePathology($pathology, $data);

            $errors = $this->validationErrors($pathology, $validator);

            if ($errors) {
                return $this->json(
                    ['Error' => $errors],
                    400
                );
            }

            $em->flush();
            return $this->json(
                $pathology,
                200,
                [],
                ['groups' => ['pathology.show', 'dates']]
            );
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(
        int $id,
        EntityManagerInterface $em,
        PathologyRepository $pathologyRepository
    ): JsonResponse
    {
        $pathology = $pathologyRepository->find($id);

        if (!$pathology) {
            return $this->notFound('Pathology');
        }
        
        $em->remove($pathology);
        $em->flush();

        return $this->json(
            null,
            204
        );
    }

    private function hydratePathology(
        Pathology $pathology, 
        array $data
    ): void
    {
        $pathology->setName($data['name']);
        $pathology->setDescription($data['description']);
        $pathology->setEstimatedRecoveryDays($data['estimatedRecoveryDays']);
    }
}
