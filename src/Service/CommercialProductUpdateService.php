<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Service;

use Aledo\PhpMiddleTestTask\Repository\CommercialProductRepositoryInterface;

class CommercialProductUpdateService
{
    public function __construct(
        private readonly CommercialProductRepositoryInterface $commercialProductRepository,
        private readonly ProductIdResolver $productIdResolver,
        private readonly CommercialProductChangesApplier $changesApplier,
    ) {
    }

    public function update(mixed $id, array $entity): CommercialProductUpdateResult
    {
        if ($this->shouldDelete($entity)) {
            return $this->delete($id);
        }

        $resolvedId = $this->productIdResolver->resolve($id);
        if ($resolvedId === null) {
            return CommercialProductUpdateResult::notFound();
        }

        $commercialProduct = $this->commercialProductRepository->find($resolvedId);
        if ($commercialProduct === null) {
            return CommercialProductUpdateResult::notFound();
        }

        $this->changesApplier->apply($commercialProduct, $entity);
        $this->commercialProductRepository->save($commercialProduct);

        return CommercialProductUpdateResult::updated($commercialProduct->toArray());
    }

    private function shouldDelete(array $entity): bool
    {
        return array_key_exists('count', $entity) && $entity['count'] < 1;
    }

    private function delete(mixed $id): CommercialProductUpdateResult
    {
        $resolvedId = $this->productIdResolver->resolve($id);
        if ($resolvedId === null) {
            return CommercialProductUpdateResult::invalidId();
        }

        $wasDeleted = $this->commercialProductRepository->delete($resolvedId);
        if (!$wasDeleted) {
            return CommercialProductUpdateResult::deleteNotFound();
        }

        return CommercialProductUpdateResult::deleted();
    }
}