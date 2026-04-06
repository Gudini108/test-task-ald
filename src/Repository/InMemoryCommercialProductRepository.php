<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Repository;

use Aledo\PhpMiddleTestTask\Domain\CommercialProduct;

/**
 * In-memory product repository for tests.
 */
final class InMemoryCommercialProductRepository implements CommercialProductRepositoryInterface
{
    /**
     * @var array<int, CommercialProduct>
     */
    private array $products = [];

    /**
     * @param CommercialProduct[] $products
     */
    public function __construct(array $products = [])
    {
        foreach ($products as $product) {
            $this->products[$product->id] = $product;
        }
    }

    public function find(?int $id): ?CommercialProduct
    {
        if ($id === null) {
            return null;
        }
        return $this->products[$id] ?? null;
    }

    public function save(CommercialProduct $product): void
    {
        $this->products[$product->id] = $product;
    }

    public function delete(int $id): bool
    {
        if (!array_key_exists($id, $this->products)) {
            return false;
        }
        unset($this->products[$id]);
        return true;
    }
}
