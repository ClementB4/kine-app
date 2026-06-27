<?php

namespace App\Controller\Api;

use App\Entity\Patient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/patients')]
final class PatientController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(
        PatientRepository $patientRepository
    ): JsonResponse
    {
        $patients = $patientRepository->findAll();

        return $this->json(
            $patients,
            200,
            [],
            ['groups' => ['patient.index']]);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(
        int $id, 
        PatientRepository $patientRepository
    ): JsonResponse
    {
        $patient = $patientRepository->find($id);

        if (!$patient) {
            return $this->json(
                ['error' => 'Patient not found'],
                404
            );
        };

        return $this->json(
            $patient,
            200,
            [],
            ['groups' => ['patient.show']]
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
        } catch (\JsonException|\Throwable $e) {
            return $this->json([
                'error' => 'Invalid JSON'
            ], 400);
        }

        $patient = new Patient();
        $this->hydratePatient($patient, $data);
        $patient->setCreatedAt(new \DateTimeImmutable());

        $em->persist($patient);
        $em->flush();
        return $this->json(
            $patient, 
            201,
            [],
            ['groups' => ['patients.show']]
        );
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        int $id, 
        Request $request, 
        PatientRepository $patientRepository, 
        EntityManagerInterface $em
    ): JsonResponse 
    {
        try {
            $data = $request->toArray();
        } catch (\JsonException|\Throwable $e) {
            return $this->json([
                'error' => 'Invalid JSON'
            ], 400);
        }

        $patient = $patientRepository->find($id);
        if (!$patient) {
            return $this->json(
                ['error' => 'Patient not found'],
                404
            );
        } else {
            $this->hydratePatient($patient, $data);
            
            $em->flush();
            return $this->json(
                ["success" => "Patient updated"], 
                200,
            );
        }
    }

    private function hydratePatient(
        Patient $patient,
        array $data
    ): void
    {
        $patient->setFirstname($data['firstName']);
        $patient->setLastname($data['lastName']);
        $patient->setBirthDate(new \DateTimeImmutable($data['birthDate']));
        $patient->setGender($data['gender']);
        $patient->setHeight($data['height']);
        $patient->setWeight($data['weight']);
        $patient->setJob($data['job']);
        $patient->setSport($data['sport']);
        $patient->setLaterality($data['laterality']);
        $patient->setComment($data['comment']);
        $patient->setUpdatedAt(new \DateTimeImmutable());
    }
}
