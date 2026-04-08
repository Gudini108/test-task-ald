<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Tests\Service;

use Aledo\PhpMiddleTestTask\Service\CommercialProductPayloadExtractor;
use PHPUnit\Framework\TestCase;

final class CommercialProductPayloadExtractorTest extends TestCase
{
    public function testExtractForUpdateReturnsEntityWhenItIsArray(): void
    {
        $extractor = new CommercialProductPayloadExtractor();

        $result = $extractor->extractForUpdate([
            'entity' => [
                'name' => 'Product',
                'count' => 5,
            ],
        ]);

        self::assertSame([
            'name' => 'Product',
            'count' => 5,
        ], $result);
    }

    public function testExtractForUpdateReturnsNullWhenEntityIsMissing(): void
    {
        $extractor = new CommercialProductPayloadExtractor();

        $result = $extractor->extractForUpdate([]);

        self::assertNull($result);
    }

    public function testExtractForUpdateReturnsNullWhenEntityIsNotArray(): void
    {
        $extractor = new CommercialProductPayloadExtractor();

        $result = $extractor->extractForUpdate([
            'entity' => 'wrong',
        ]);

        self::assertNull($result);
    }

    public function testExtractForPatchReturnsEntityWhenWrappedIntoEntityKey(): void
    {
        $extractor = new CommercialProductPayloadExtractor();

        $result = $extractor->extractForPatch([
            'entity' => [
                'name' => 'Product',
            ],
        ]);

        self::assertSame([
            'name' => 'Product',
        ], $result);
    }

    public function testExtractForPatchReturnsFlatPayloadAsIs(): void
    {
        $extractor = new CommercialProductPayloadExtractor();

        $result = $extractor->extractForPatch([
            'name' => 'Product',
            'count' => 3,
        ]);

        self::assertSame([
            'name' => 'Product',
            'count' => 3,
        ], $result);
    }

    public function testExtractForPatchReturnsNullWhenPayloadIsEmpty(): void
    {
        $extractor = new CommercialProductPayloadExtractor();

        $result = $extractor->extractForPatch([]);

        self::assertNull($result);
    }

    public function testExtractForPatchReturnsNullWhenEntityIsNotArray(): void
    {
        $extractor = new CommercialProductPayloadExtractor();

        $result = $extractor->extractForPatch([
            'entity' => 'wrong',
        ]);

        self::assertNull($result);
    }
}