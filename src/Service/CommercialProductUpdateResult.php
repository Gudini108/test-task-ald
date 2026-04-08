<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Service;

final readonly class CommercialProductUpdateResult
{
    private function __construct(
        public int   $statusCode,
        public array $payload = [],
    ) {
    }

    public static function updated(array $payload): self
    {
        return new self(200, $payload);
    }

    public static function deleted(): self
    {
        return new self(200, ['message' => 'Product deleted.']);
    }

    public static function invalidId(): self
    {
        return new self(400, ['message' => 'Invalid product id.']);
    }

    public static function deleteNotFound(): self
    {
        return new self(404, ['message' => 'Product not found.']);
    }

    public static function notFound(): self
    {
        return new self(404);
    }
}