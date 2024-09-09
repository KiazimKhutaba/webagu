<?php

namespace App\Modules\File\Dtos;

use DateTime;

class CreatedFile
{
    public function __construct(
        public readonly int $id,
        public readonly string $original_name,
        public readonly string $generated_name,
        public readonly string $type,
        public readonly int $size,
        public readonly DateTime $created_at,
    )
    {
    }

    /**
     *
     * @param array $data
     * @return static
     */
    public static function from(array $data): self
    {
        return new self(
            id: $data['id'],
            original_name: $data['generated_name'],
            generated_name: $data['generated_name'],
            type: $data['type'],
            size: $data['size'],
            created_at: $data['created_at'],
        );
    }

    /**
     * @link https://stackoverflow.com/a/16111687/19233386
     *
     * @return mixed
     */
    public function toArray(): array
    {
        return json_decode(json_encode($this), true);
    }


    public function __toString(): string
    {
        return json_encode($this, true);
    }
}
