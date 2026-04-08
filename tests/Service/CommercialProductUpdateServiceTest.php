<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Tests\Service;

use Aledo\PhpMiddleTestTask\Domain\CommercialProduct;
use Aledo\PhpMiddleTestTask\Repository\CommercialProductRepositoryInterface;
use Aledo\PhpMiddleTestTask\Repository\UuidFileRepositoryInterface;
use Aledo\PhpMiddleTestTask\Service\CommercialProductChangesApplier;
use Aledo\PhpMiddleTestTask\Service\CommercialProductUpdateResult;
use Aledo\PhpMiddleTestTask\Service\CommercialProductUpdateService;
use Aledo\PhpMiddleTestTask\Service\ProductIdResolver;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

final class CommercialProductUpdateServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testUpdateDeletesProductWhenCountIsLessThanOne(): void
    {
        $repository = $this->createMock(CommercialProductRepositoryInterface::class);
        $uuidFileRepository = $this->createMock(UuidFileRepositoryInterface::class);

        $repository->expects(self::once())
            ->method('delete')
            ->with(10)
            ->willReturn(true);

        $repository->expects(self::never())->method('find');
        $repository->expects(self::never())->method('save');
        $uuidFileRepository->expects(self::never())->method('findOneByUuid');

        $service = new CommercialProductUpdateService(
            $repository,
            new ProductIdResolver(),
            new CommercialProductChangesApplier($uuidFileRepository),
        );

        $result = $service->update(10, ['count' => 0]);

        self::assertEquals(
            CommercialProductUpdateResult::deleted(),
            $result,
        );
    }

    /**
     * @throws Exception
     */
    public function testUpdateReturnsBadRequestWhenDeleteIdIsInvalid(): void
    {
        $repository = $this->createMock(CommercialProductRepositoryInterface::class);
        $uuidFileRepository = $this->createMock(UuidFileRepositoryInterface::class);

        $repository->expects(self::never())->method('delete');
        $repository->expects(self::never())->method('find');
        $repository->expects(self::never())->method('save');
        $uuidFileRepository->expects(self::never())->method('findOneByUuid');

        $service = new CommercialProductUpdateService(
            $repository,
            new ProductIdResolver(),
            new CommercialProductChangesApplier($uuidFileRepository),
        );

        $result = $service->update('wrong-id', ['count' => 0]);

        self::assertEquals(
            CommercialProductUpdateResult::invalidId(),
            $result,
        );
    }

    /**
     * @throws Exception
     */
    public function testUpdateReturnsNotFoundWhenDeleteFails(): void
    {
        $repository = $this->createMock(CommercialProductRepositoryInterface::class);
        $uuidFileRepository = $this->createMock(UuidFileRepositoryInterface::class);

        $repository->expects(self::once())
            ->method('delete')
            ->with(10)
            ->willReturn(false);

        $repository->expects(self::never())->method('find');
        $repository->expects(self::never())->method('save');
        $uuidFileRepository->expects(self::never())->method('findOneByUuid');

        $service = new CommercialProductUpdateService(
            $repository,
            new ProductIdResolver(),
            new CommercialProductChangesApplier($uuidFileRepository),
        );

        $result = $service->update(10, ['count' => 0]);

        self::assertEquals(
            CommercialProductUpdateResult::deleteNotFound(),
            $result,
        );
    }

    /**
     * @throws Exception
     */
    public function testUpdateReturnsNotFoundWhenIdIsInvalidForRegularUpdate(): void
    {
        $repository = $this->createMock(CommercialProductRepositoryInterface::class);
        $uuidFileRepository = $this->createMock(UuidFileRepositoryInterface::class);

        $repository->expects(self::never())->method('find');
        $repository->expects(self::never())->method('save');
        $repository->expects(self::never())->method('delete');
        $uuidFileRepository->expects(self::never())->method('findOneByUuid');

        $service = new CommercialProductUpdateService(
            $repository,
            new ProductIdResolver(),
            new CommercialProductChangesApplier($uuidFileRepository),
        );

        $result = $service->update('wrong-id', ['name' => 'Updated']);

        self::assertEquals(
            CommercialProductUpdateResult::notFound(),
            $result,
        );
    }

    /**
     * @throws Exception
     */
    public function testUpdateReturnsNotFoundWhenProductDoesNotExist(): void
    {
        $repository = $this->createMock(CommercialProductRepositoryInterface::class);
        $uuidFileRepository = $this->createMock(UuidFileRepositoryInterface::class);

        $repository->expects(self::once())
            ->method('find')
            ->with(10)
            ->willReturn(null);

        $repository->expects(self::never())->method('save');
        $repository->expects(self::never())->method('delete');
        $uuidFileRepository->expects(self::never())->method('findOneByUuid');

        $service = new CommercialProductUpdateService(
            $repository,
            new ProductIdResolver(),
            new CommercialProductChangesApplier($uuidFileRepository),
        );

        $result = $service->update(10, ['name' => 'Updated']);

        self::assertEquals(
            CommercialProductUpdateResult::notFound(),
            $result,
        );
    }

    /**
     * @throws Exception
     */
    public function testUpdateAppliesChangesSavesProductAndReturnsUpdatedResult(): void
    {
        $product = new CommercialProduct(
            id: 10,
            name: 'Old product',
            count: 2,
            price: 99.99,
            type: CommercialProduct::TYPES['product'],
            comment: 'Old comment',
        );

        $repository = $this->createMock(CommercialProductRepositoryInterface::class);
        $uuidFileRepository = $this->createMock(UuidFileRepositoryInterface::class);

        $repository->expects(self::once())
            ->method('find')
            ->with(10)
            ->willReturn($product);

        $repository->expects(self::once())
            ->method('save')
            ->with($product);

        $repository->expects(self::never())->method('delete');
        $uuidFileRepository->expects(self::never())->method('findOneByUuid');

        $service = new CommercialProductUpdateService(
            $repository,
            new ProductIdResolver(),
            new CommercialProductChangesApplier($uuidFileRepository),
        );

        $result = $service->update(10, [
            'name' => 'Updated product',
            'comment' => 'Updated comment',
        ]);

        self::assertSame('Updated product', $product->name);
        self::assertSame('Updated comment', $product->comment);

        self::assertEquals(
            CommercialProductUpdateResult::updated($product->toArray()),
            $result,
        );
    }

    /**
     * @throws Exception
     */
    public function testUpdateDoesNotDeleteWhenCountIsOne(): void
    {
        $product = new CommercialProduct(
            id: 10,
            name: 'Product',
            count: 5,
            price: 99.99,
            type: CommercialProduct::TYPES['product'],
        );

        $repository = $this->createMock(CommercialProductRepositoryInterface::class);
        $uuidFileRepository = $this->createMock(UuidFileRepositoryInterface::class);

        $repository->expects(self::once())
            ->method('find')
            ->with(10)
            ->willReturn($product);

        $repository->expects(self::once())
            ->method('save')
            ->with($product);

        $repository->expects(self::never())->method('delete');
        $uuidFileRepository->expects(self::never())->method('findOneByUuid');

        $service = new CommercialProductUpdateService(
            $repository,
            new ProductIdResolver(),
            new CommercialProductChangesApplier($uuidFileRepository),
        );

        $result = $service->update(10, ['count' => 1]);

        self::assertSame(1, $product->count);

        self::assertEquals(
            CommercialProductUpdateResult::updated($product->toArray()),
            $result,
        );
    }
}