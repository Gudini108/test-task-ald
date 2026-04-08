<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Tests\Controller;

use Aledo\PhpMiddleTestTask\Controller\CommercialProductController;
use Aledo\PhpMiddleTestTask\Http\JsonResponse;
use Aledo\PhpMiddleTestTask\Service\CommercialProductPayloadExtractor;
use Aledo\PhpMiddleTestTask\Service\CommercialProductUpdateResult;
use Aledo\PhpMiddleTestTask\Service\CommercialProductUpdateService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

final class CommercialProductControllerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testUpdateReturns400WhenPayloadIsInvalid(): void
    {
        $payloadExtractor = $this->createMock(CommercialProductPayloadExtractor::class);
        $updateService = $this->createMock(CommercialProductUpdateService::class);

        $payloadExtractor->expects(self::once())
            ->method('extractForUpdate')
            ->with([])
            ->willReturn(null);

        $updateService->expects(self::never())
            ->method('update');

        $controller = new CommercialProductController($payloadExtractor, $updateService);

        $response = $controller->update(10, []);

        self::assertEquals(new JsonResponse(400), $response);
    }

    /**
     * @throws Exception
     */
    public function testPatchReturns400WhenPayloadIsInvalid(): void
    {
        $payloadExtractor = $this->createMock(CommercialProductPayloadExtractor::class);
        $updateService = $this->createMock(CommercialProductUpdateService::class);

        $payloadExtractor->expects(self::once())
            ->method('extractForPatch')
            ->with([])
            ->willReturn(null);

        $updateService->expects(self::never())
            ->method('update');

        $controller = new CommercialProductController($payloadExtractor, $updateService);

        $response = $controller->patch(10, []);

        self::assertEquals(new JsonResponse(400), $response);
    }

    /**
     * @throws Exception
     */
    public function testUpdateDelegatesToServiceAndReturnsResponse(): void
    {
        $payload = ['name' => 'Updated product'];
        $result = CommercialProductUpdateResult::updated([
            'id' => 10,
            'name' => 'Updated product',
        ]);

        $payloadExtractor = $this->createMock(CommercialProductPayloadExtractor::class);
        $updateService = $this->createMock(CommercialProductUpdateService::class);

        $payloadExtractor->expects(self::once())
            ->method('extractForUpdate')
            ->with(['entity' => $payload])
            ->willReturn($payload);

        $updateService->expects(self::once())
            ->method('update')
            ->with(10, $payload)
            ->willReturn($result);

        $controller = new CommercialProductController($payloadExtractor, $updateService);

        $response = $controller->update(10, ['entity' => $payload]);

        self::assertEquals(
            new JsonResponse(200, [
                'id' => 10,
                'name' => 'Updated product',
            ]),
            $response,
        );
    }

    /**
     * @throws Exception
     */
    public function testPatchDelegatesToServiceAndReturnsResponse(): void
    {
        $payload = ['name' => 'Patched product'];
        $result = CommercialProductUpdateResult::updated([
            'id' => 10,
            'name' => 'Patched product',
        ]);

        $payloadExtractor = $this->createMock(CommercialProductPayloadExtractor::class);
        $updateService = $this->createMock(CommercialProductUpdateService::class);

        $payloadExtractor->expects(self::once())
            ->method('extractForPatch')
            ->with($payload)
            ->willReturn($payload);

        $updateService->expects(self::once())
            ->method('update')
            ->with(10, $payload)
            ->willReturn($result);

        $controller = new CommercialProductController($payloadExtractor, $updateService);

        $response = $controller->patch(10, $payload);

        self::assertEquals(
            new JsonResponse(200, [
                'id' => 10,
                'name' => 'Patched product',
            ]),
            $response,
        );
    }
}