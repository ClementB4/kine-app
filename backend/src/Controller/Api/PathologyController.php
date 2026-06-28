<?php

namespace App\Controller\Api;

use App\Entity\Pathology;
use App\Repository\PathologyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/pathologies')]
final class PathologyController extends AbstractController
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
            return $this->json(
                ['Error' => 'Pathology not found.'],
                404
            );
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
        EntityManagerInterface $em
    ): JsonResponse
    {
        try {
            $data = $request->toArray();
        } catch (\JsonException) {
            return $this->json(
                ['error' => 'Invalid JSON'], 
                400
            );
        }

        $pathology = new Pathology();
        $this->hydratePathology($pathology, $data);
        $pathology->setCreatedAt(new \DateTimeImmutable());

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
        Request $request
    ): JsonResponse
    {
        try {
            $data = $request->toArray();
        } catch (\JsonException) {
            return $this->json(
                ['error' => 'Invalid JSON'], 
                400
            );
        }

        $pathology = $pathologyRepository->find($id);
        if (!$pathology) {
            return $this->json(
                ['Error' => 'Pathology not found.'],
                404
            );
        } else {
            $this->hydratePathology($pathology, $data);

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
    )
    {
        $pathology = $pathologyRepository->find($id);

        if (!$pathology) {
            return $this->json(
                ['Error' => 'Pathology not found.'],
                404
            );
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
        $pathology->setUpdatedAt(new \DateTimeImmutable());
    }
}
