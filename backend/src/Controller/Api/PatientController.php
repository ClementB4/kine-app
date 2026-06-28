<?php

namespace App\Controller\Api;

use App\Entity\Patient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/patients')]
final class PatientController extends BaseApiController
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
            return $this->notFound('Patient');
        };

        return $this->json(
            $patient,
            200,
            [],
            ['groups' => ['patient.show', 'dates']]
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

        $patient = new Patient();
        $this->hydratePatient($patient, $data);
        
        $errors = $this->validationErrors($patient, $validator);

        if ($errors) {
            return $this->json(
                ['Error' => $errors],
                400
            );
        }

        $em->persist($patient);
        $em->flush();
        return $this->json(
            $patient, 
            201,
            [],
            ['groups' => ['patient.show', 'dates']]
        );
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        int $id, 
        Request $request, 
        PatientRepository $patientRepository, 
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse 
    {
        $data = $this->parseJson($request);

        $patient = $patientRepository->find($id);
        if (!$patient) {
            return $this->notFound('Patient');
        } else {
            $this->hydratePatient($patient, $data);

            $errors = $this->validationErrors($patient, $validator);

            if ($errors) {
                return $this->json(
                    ['Error' => $errors],
                    400
                );
            }
            
            $em->flush();
            return $this->json(
                $patient, 
                200,
                [],
                ['groups' => ['patient.show', 'dates']]
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
    }
}
