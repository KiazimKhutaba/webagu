<?php

namespace App\Modules\File\Dtos;

use Stringable;

class CkEditorFileUploaded implements Stringable
{
    private function __construct(
        private readonly string $url
    )
    {
    }

    public static function from(string $url): self
    {
        return new self($url);
    }

    /**
     * @see https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/simple-upload-adapter.html#successful-upload
     *
     * @return string[]
     */
    public function toArray(): array
    {
        return ['url' => "/upload/$this->url"];
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }


}
