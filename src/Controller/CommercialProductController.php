<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Controller;

use Aledo\PhpMiddleTestTask\Domain\CommercialProduct;
use Aledo\PhpMiddleTestTask\Http\JsonResponse;
use Aledo\PhpMiddleTestTask\Repository\CommercialProductRepositoryInterface;
use Aledo\PhpMiddleTestTask\Repository\UuidFileRepositoryInterface;

/**
 * Intentionally legacy controller inspired by production code.
 */
final class CommercialProductController
{
    private const array ENTITY_RELATION_FIELDS = ['image', 'image_uuid'];

    public function __construct(
        private readonly CommercialProductRepositoryInterface $commercialProductRepository,
        private readonly UuidFileRepositoryInterface $uuidFileRepository,
    ) {
    }

    public function update(mixed $id, array $data): JsonResponse
    {
        $entity = $data['entity'] ?? null;
        if (!is_array($entity)) {
            return new JsonResponse(400);
        }
        return $this->updateProductEntity($id, $entity);
    }

    public function patch(mixed $id, array $data): JsonResponse
    {
        $entity = $data['entity'] ?? $data;
        if (!is_array($entity) || [] === $entity) {
            return new JsonResponse(400);
        }
        return $this->updateProductEntity($id, $entity);
    }

    /** todo: extract contract for request payload. */
    private function updateProductEntity(mixed $id, array $entity): JsonResponse
    {
        if (array_key_exists('count', $entity) && $entity['count'] < 1) {
            return $this->delete($id);
        }
        //<< fixme
        $id = is_numeric($id) ? (int) $id : null;
        $commercialProduct = $this->commercialProductRepository->find($id);
        if (null === $commercialProduct) {
            return new JsonResponse(404);
        }
        if (array_key_exists('image_uuid', $entity)) {
            $imageUuid = $entity['image_uuid'] ?: null;
            $image = $imageUuid ? $this->uuidFileRepository->findOneByUuid($imageUuid) : null;
            $commercialProduct->setImageUuid($imageUuid);
            $commercialProduct->setImage($image);
        }
        foreach ($entity as $field => $value) {
            if (in_array($field, self::ENTITY_RELATION_FIELDS, true)) {
                continue;
            }
            $commercialProduct->$field = $value;
        }
        $this->commercialProductRepository->save($commercialProduct);
        return new JsonResponse(200, $commercialProduct->toArray());
    }

    /** TODO: error handling & better response message. */
    private function delete(mixed $id): JsonResponse
    {
        $resolvedId = is_numeric($id) ? (int) $id : null;
        if ($resolvedId === null) {
            return new JsonResponse(400, ['message' => 'Invalid product id.']);
        }
        $wasDeleted = $this->commercialProductRepository->delete($resolvedId);
        if (!$wasDeleted) {
            return new JsonResponse(404, ['message' => 'Product not found.']);
        }
        return new JsonResponse(200, ['message' => 'Product deleted.']);
    }
}
