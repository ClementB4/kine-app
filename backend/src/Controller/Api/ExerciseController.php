<?php

namespace App\Controller\Api;

use App\Entity\Exercise;
use App\Enum\ExerciseCategory;
use App\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/exercises')]
final class ExerciseController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(
        ExerciseRepository $exerciseRepository
    ): JsonResponse
    {
        $exercises = $exerciseRepository->findAll();

        return $this->json(
            $exercises,
            200,
            [],
            ['groups' => 'exercise.index']
        );
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(
        int $id,
        ExerciseRepository $exerciseRepository
    ): JsonResponse
    {
        $exercise = $exerciseRepository->find($id);

        if (!$exercise) {
            return $this->json(
                ['Error' => 'Exercise not found.'],
                404
            );
        }

        return $this->json(
            $exercise,
            200,
            [],
            ['groups' => ['exercise.show']]
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

        $exercise = new Exercise();
        $this->hydrateExercise($exercise, $data);
        $exercise->setCreatedAt(new \DateTimeImmutable());

        $em->persist($exercise);
        $em->flush();
        return $this->json(
            $exercise,
            201,
            [],
            ['groups' => ['exercise.show']]
        );
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        int $id,
        ExerciseRepository $exerciseRepository,
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

        $exercise = $exerciseRepository->find($id);
        if (!$exercise) {
            return $this->json(
                ['Error' => 'Exercise not found.'],
                404
            );
        } else {
            $this->hydrateExercise($exercise, $data);

            $em->flush();
            return $this->json(
                $exercise,
                200,
                [],
                ['groups' => ['exercise.show']]
            );
        }
    }

    private function hydrateExercise(
        Exercise $exercise, 
        array $data
    ): void
    {
        $exercise->setName($data['name']);
        $exercise->setDescription($data['description']);
        $exercise->setVideoUrl($data['videoUrl']);
        $exercise->setCategory(ExerciseCategory::from($data['category']));
        $exercise->setUpdatedAt(new \DateTimeImmutable());
    }
}
