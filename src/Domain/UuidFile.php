<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Domain;

/**
 * Legacy file model used by the task.
 */
final class UuidFile
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $name,
        public readonly string $path,
    ) {
    }

    /**
     * @return array{uuid: string, name: string, path: string}
     */
    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'path' => $this->path,
        ];
    }
}
