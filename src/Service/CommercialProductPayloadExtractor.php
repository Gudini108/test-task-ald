<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Service;

class CommercialProductPayloadExtractor
{
    private const string ENTITY = 'entity';

    public function extractForUpdate(array $data): ?array
    {
        $entity = $data[self::ENTITY] ?? null;

        return is_array($entity) ? $entity : null;
    }

    public function extractForPatch(array $data): ?array
    {
        $entity = $data[self::ENTITY] ?? $data;

        if (!is_array($entity) || $entity === []) {
            return null;
        }

        return $entity;
    }
}