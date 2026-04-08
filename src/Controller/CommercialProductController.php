<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Controller;

use Aledo\PhpMiddleTestTask\Http\JsonResponse;
use Aledo\PhpMiddleTestTask\Service\CommercialProductUpdateService;
use Aledo\PhpMiddleTestTask\Service\CommercialProductPayloadExtractor;

/**
 * Intentionally legacy controller inspired by production code.
 */
final class CommercialProductController
{
    public function __construct(
        private readonly CommercialProductPayloadExtractor $payloadExtractor,
        private readonly CommercialProductUpdateService $updateService,
    ) {
    }

    public function update(mixed $id, array $data): JsonResponse
    {
        $entity = $this->payloadExtractor->extractForUpdate($data);
        if ($entity === null) {
            return new JsonResponse(400);
        }
        $result = $this->updateService->update($id, $entity);
        return new JsonResponse($result->statusCode, $result->payload);
    }

    public function patch(mixed $id, array $data): JsonResponse
    {
        $entity = $this->payloadExtractor->extractForPatch($data);
        if ($entity === null) {
            return new JsonResponse(400);
        }
        $result = $this->updateService->update($id, $entity);
        return new JsonResponse($result->statusCode, $result->payload);
    }
}
