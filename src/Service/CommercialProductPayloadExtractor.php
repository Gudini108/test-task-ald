<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Service;

class CommercialProductPayloadExtractor
{
    public function extractForUpdate(array $data): ?array
    {
        return $this->extractEntity($data);
    }

    public function extractForPatch(array $data): ?array
    {
        return $this->extractEntity($data);
    }

    private function extractEntity(array $data): ?array
    {
        $entity = $data['entity'] ?? $data;

        if (!is_array($entity) || $entity === []) {
            return null;
        }

        return $entity;
    }
}