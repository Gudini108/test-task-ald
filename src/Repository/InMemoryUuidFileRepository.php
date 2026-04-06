<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Repository;

use Aledo\PhpMiddleTestTask\Domain\UuidFile;

/**
 * In-memory file repository for tests.
 */
final class InMemoryUuidFileRepository implements UuidFileRepositoryInterface
{
    /**
     * @var array<string, UuidFile>
     */
    private array $files = [];

    /**
     * @param UuidFile[] $files
     */
    public function __construct(array $files = [])
    {
        foreach ($files as $file) {
            $this->files[$file->uuid] = $file;
        }
    }

    public function findOneByUuid(string $uuid): ?UuidFile
    {
        return $this->files[$uuid] ?? null;
    }
}
