<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Service;

class ProductIdResolver
{
    public function resolve(mixed $rawId): ?int
    {
        if (!is_numeric($rawId)) {
            return null;
        }

        return (int) $rawId;
    }
}