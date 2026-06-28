<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface as ValidatorValidatorInterface;

abstract class BaseApiController extends AbstractController
{
    protected function parseJson(Request $request): array
    {
        try {
            return $request->toArray();
        } catch (\JsonException) {
            throw new BadRequestHttpException('Invalid JSON');
        }
    }

    protected function validationErrors(
        object $entity,
        ValidatorValidatorInterface $validator
    ): array
    {
        $errors = $validator->validate($entity);

        $formatted = [];

        foreach ($errors as $error) {
            $formatted[] = [
                'field' => $error->getPropertyPath(),
                'message' => $error->getMessage(),
            ];
        }

        return $formatted;
    }

    protected function notFound(string $resource): JsonResponse
    {
        return $this->json([
            'error' => "$resource not found"
        ], 404);
    }
}