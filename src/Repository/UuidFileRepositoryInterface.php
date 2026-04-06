<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Repository;

use Aledo\PhpMiddleTestTask\Domain\UuidFile;

/**
 * Repository contract for UUID files.
 */
interface UuidFileRepositoryInterface
{
    public function findOneByUuid(string $uuid): ?UuidFile;
}
