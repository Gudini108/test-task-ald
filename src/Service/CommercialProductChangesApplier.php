<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Service;

use Aledo\PhpMiddleTestTask\Domain\CommercialProduct;
use Aledo\PhpMiddleTestTask\Repository\UuidFileRepositoryInterface;

class CommercialProductChangesApplier
{
    private const string IMAGE_UUID = 'image_uuid';
    private const string IMAGE = 'image';
    private const array RELATION_FIELDS = [self::IMAGE, self::IMAGE_UUID];

    public function __construct(
        private readonly UuidFileRepositoryInterface $uuidFileRepository,
    ) {
    }

    public function apply(CommercialProduct $commercialProduct, array $entity): void
    {
        $this->applyImage($commercialProduct, $entity);

        foreach ($entity as $field => $value) {
            if (in_array($field, self::RELATION_FIELDS, true)) {
                continue;
            }

            $commercialProduct->$field = $value;
        }
    }

    private function applyImage(CommercialProduct $commercialProduct, array $entity): void
    {
        if (!array_key_exists(self::IMAGE_UUID, $entity)) {
            return;
        }

        $imageUuid = $entity[self::IMAGE_UUID] ?: null;
        $image = $imageUuid !== null
            ? $this->uuidFileRepository->findOneByUuid($imageUuid)
            : null;

        $commercialProduct->setImageUuid($imageUuid);
        $commercialProduct->setImage($image);
    }
}