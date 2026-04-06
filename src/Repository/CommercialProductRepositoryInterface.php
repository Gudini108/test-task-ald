<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Repository;

use Aledo\PhpMiddleTestTask\Domain\CommercialProduct;

/**
 * Repository contract for commercial products.
 */
interface CommercialProductRepositoryInterface
{
    public function find(?int $id): ?CommercialProduct;

    public function save(CommercialProduct $product): void;

    public function delete(int $id): bool;
}
