<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Http;

/**
 * Minimal response wrapper used by the exercise.
 */
final class JsonResponse
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        private readonly int $statusCode,
        private readonly array $payload = [],
    ) {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array<string, mixed>
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
