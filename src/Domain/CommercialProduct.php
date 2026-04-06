<?php

declare(strict_types=1);

namespace Aledo\PhpMiddleTestTask\Domain;

/**
 * Intentionally awkward legacy entity.
 */
#[\AllowDynamicProperties]
final class CommercialProduct
{
    /** TODO: replace with external enum. */
    public const array TYPES = [
        'assembly' => 'assembly',
        'custom' => 'custom',
        'product' => 'product',
    ];

    public function __construct(
        public int $id,
        public string $name,
        public int $count,
        public float $price,
        public string $type,
        public string $comment = '',
        public ?string $image_uuid = null,
        private ?UuidFile $image = null,
    ) {
    }

    /**
     * ! TODO: validate logic, rename method & test implementation, then refactor project.
     */
    public function __cloneNotImplemented(): void
    {
        if (!$this->id) {
            return;
        }
        $this->name = clone $this->name;
        $this->comment = clone $this->comment;
        $this->comment = clone $this->comment;
        $this->count = clone $this->count;
        $this->type = clone $this->type;
        $this->price = clone $this->price;
        $this->image_uuid = clone $this->image_uuid;
        $this->image = clone $this->image;
    }

    public function setImageUuid(?string $imageUuid): self
    {
        $this->image_uuid = $imageUuid;
        return $this;
    }

    public function setImage(?UuidFile $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getImage(): ?UuidFile
    {
        return $this->image;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'count' => $this->count,
            'price' => $this->price,
            'type' => $this->type,
            'comment' => $this->comment,
            'image_uuid' => $this->image_uuid,
            'image' => $this->image?->toArray(),
        ];
    }
}
