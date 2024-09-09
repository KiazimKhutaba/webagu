<?php

namespace App\Modules\File\Dtos;

use App\Common\Enums\FileableType;
use App\Modules\File\Enums\FileDestinations;
use Illuminate\Http\UploadedFile;

class UploadedFileDto
{
    private ?int $id = null;
    private int $user_id;
    private string $original_name;
    private string $generated_name;
    private string $mime_type;
    private int $size;
    private FileableType $fileable_type;
    private int $fileable_id;
    private string $extension;


    public static function createFromUploadedFile(UploadedFile $file, int $user_id, string $fileable_type, int $fileable_id): self
    {
        $original_name = $file->getClientOriginalName();
        $generated_name = sprintf('%s.%s', md5($original_name . microtime(true)), $file->extension());

        return (new self())
            ->setUserId($user_id)
            ->setOriginalName($original_name)
            ->setGeneratedName($generated_name)
            ->setMimeType($file->getClientMimeType())
            ->setSize($file->getSize())
            ->setFileableType(FileableType::from($fileable_type))
            ->setFileableId($fileable_id)
            ->setExtension($file->extension());
    }


    public function toArray(): array
    {
        $data = [
            'user_id' => $this->user_id,
            'original_name' => $this->original_name,
            'generated_name' => $this->generated_name,
            'type' => $this->mime_type,
            'size' => $this->size,
            'fileable_type' => $this->fileable_type->value,
            'fileable_id' => $this->fileable_id,
            'extension' => $this->extension,
        ];

        if(null !== $this?->id) {
            $data['id'] = $this->id;
        }

        return $data;
    }

    public function getSavePath(FileDestinations $destination): string
    {
        return public_path($destination->path()) . DIRECTORY_SEPARATOR . $this->generated_name;
    }

    public function __toString(): string
    {
        return json_encode($this, true);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getOriginalName(): string
    {
        return $this->original_name;
    }

    public function setOriginalName(string $original_name): self
    {
        $this->original_name = $original_name;
        return $this;
    }

    public function getGeneratedName(): string
    {
        return $this->generated_name;
    }

    public function setGeneratedName(string $generated_name): self
    {
        $this->generated_name = $generated_name;
        return $this;
    }

    public function getMimeType(): string
    {
        return $this->mime_type;
    }

    public function setMimeType(string $mime_type): self
    {
        $this->mime_type = $mime_type;
        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function getFileableType(): FileableType
    {
        return $this->fileable_type;
    }

    public function setFileableType(FileableType $fileable_type): self
    {
        $this->fileable_type = $fileable_type;
        return $this;
    }

    public function getFileableId(): int
    {
        return $this->fileable_id;
    }

    public function setFileableId(int $fileable_id): self
    {
        $this->fileable_id = $fileable_id;
        return $this;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;
        return $this;
    }

}
