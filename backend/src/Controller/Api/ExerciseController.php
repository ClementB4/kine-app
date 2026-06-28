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
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
            ['groups' => ['exercise.show', 'dates']]
        );
    }

    #[Route('', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator
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

        $errors = $validator->validate($exercise);
        if (count($errors) > 0) {
            return $this->json(
                (string) $errors, 
                400
            );
        }

        $em->persist($exercise);
        $em->flush();
        return $this->json(
            $exercise,
            201,
            [],
            ['groups' => ['exercise.show', 'dates']]
        );
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        int $id,
        ExerciseRepository $exerciseRepository,
        EntityManagerInterface $em,
        Request $request,
        ValidatorInterface $validator
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

            $formattedErrors = [];

            $errors = $validator->validate($exercise);
            if (count($errors) > 0) {
                foreach($errors as $error) {
                    $formattedErrors[] = [
                        'field' => $error->getPropertyPath(),
                        'message' => $error->getMessage()
                    ];
                };
                return $this->json(
                    ['errors' => $formattedErrors], 
                    400
                );
            }

            $em->flush();
            return $this->json(
                $exercise,
                200,
                [],
                ['groups' => ['exercise.show', 'dates']]
            );
        }
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(
        int $id,
        EntityManagerInterface $em,
        ExerciseRepository $exerciseRepository
    )
    {
        $exercise = $exerciseRepository->find($id);

        if (!$exercise) {
            return $this->json(
                ['Error' => 'Exercise not found.'],
                404
            );
        }
        
        $em->remove($exercise);
        $em->flush();

        return $this->json(
            null,
            204
        );
    }

    private function hydrateExercise(
        Exercise $exercise, 
        array $data
    ): void
    {
        $exercise->setName($data['name']);
        $exercise->setDescription($data['description']);
        $exercise->setVideoUrl($data['videoUrl']);
        $exercise->setCategory(ExerciseCategory::tryFrom($data['category']));
    }
}
