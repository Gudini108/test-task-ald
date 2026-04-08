<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Tests\Service;

use Aledo\PhpMiddleTestTask\Service\CommercialProductPayloadExtractor;
use PHPUnit\Framework\TestCase;

final class CommercialProductPayloadExtractorTest extends TestCase
{
    public function testExtractForUpdateReturnsWrappedEntity(): void
    {
        $extractor = new CommercialProductPayloadExtractor();

        $result = $extractor->extractForUpdate([
            'entity' => [
                'name' => 'Светильник',
                'count' => 2,
            ],
        ]);

        self::assertSame([
            'name' => 'Светильник',
            'count' => 2,
        ], $result);
    }

    public function testExtractForUpdateReturnsFlatPayload(): void
    {
        $extractor = new CommercialProductPayloadExtractor();

        $result = $extractor->extractForUpdate([
            'name' => 'Светильник',
            'count' => 2,
        ]);

        self::assertSame([
            'name' => 'Светильник',
            'count' => 2,
        ], $result);
    }

    public function testExtractForUpdateReturnsNullWhenPayloadIsEmpty(): void
    {
        $extractor = new CommercialProductPayloadExtractor();

        $result = $extractor->extractForUpdate([]);

        self::assertNull($result);
    }

    public function testExtractForPatchReturnsWrappedEntity(): void
    {
        $extractor = new CommercialProductPayloadExtractor();

        $result = $extractor->extractForPatch([
            'entity' => [
                'name' => 'Светильник',
                'count' => 2,
            ],
        ]);

        self::assertSame([
            'name' => 'Светильник',
            'count' => 2,
        ], $result);
    }

    public function testExtractForPatchReturnsFlatPayload(): void
    {
        $extractor = new CommercialProductPayloadExtractor();

        $result = $extractor->extractForPatch([
            'name' => 'Светильник',
            'count' => 2,
        ]);

        self::assertSame([
            'name' => 'Светильник',
            'count' => 2,
        ], $result);
    }

    public function testExtractForPatchReturnsNullWhenPayloadIsEmpty(): void
    {
        $extractor = new CommercialProductPayloadExtractor();

        $result = $extractor->extractForPatch([]);

        self::assertNull($result);
    }
}