<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PatientRepository;

#[Route('/api/patients')]
final class PatientController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(PatientRepository $patientRepository): JsonResponse
    {
        $patients = $patientRepository->findAll();

        return $this->json(
            $patients,
            JsonResponse::HTTP_OK,
            [],
            ['groups' => ['patient.index']]);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id, PatientRepository $patientRepository): JsonResponse
    {
        $patient = $patientRepository->find($id);

        return $this->json(
            $patient,
            JsonResponse::HTTP_OK,
            [],
            ['groups' => ['patient.show']]
        );
    }
}
