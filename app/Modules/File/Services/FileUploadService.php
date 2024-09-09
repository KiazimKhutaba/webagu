<?php

namespace App\Modules\File\Services;

use App\Common\Enums\FileableType;
use App\Modules\Authentication\Services\AuthenticatedUser;
use App\Modules\File\Dtos\CreatedFile;
use App\Modules\File\Dtos\UploadedFileDto;
use App\Modules\File\Enums\FileDestinations;
use App\Modules\File\Models\File;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;

class FileUploadService
{
    public function __construct(
        private readonly ImageManager $imageManager
    )
    {
    }

    public function uploadX(
        UploadedFile  $file, AuthenticatedUser $user,
        ?FileableType $fileable_type = null, ?string $fileable_id = null,
        string        $destination = 'uploads',
    ): CreatedFile
    {
        return $this->upload($file, $user, $destination, $fileable_type?->value, $fileable_id);
    }


    public function uploadFile(
        UploadedFile $uploaded_file, AuthenticatedUser $user, FileDestinations $destination,
        string $fileable_type, string $fileable_id
    ): UploadedFileDto
    {
        $dto = UploadedFileDto::createFromUploadedFile($uploaded_file, $user->id, $fileable_type, $fileable_id);
        $this->resizeImage($uploaded_file, 700, 30, $dto->getSavePath($destination));

        $new_file = File::create($dto->toArray());
        $dto->setId($new_file->id);

        return $dto;
    }


    public function resizeImage(UploadedFile $file, int $width, int $quality, string $image_path): Image
    {
        $image = $this->imageManager->make($file)->orientate();
        $resize_callback = function ($constraint) {
            $constraint->aspectRatio();
        };

        return $image->resize($width, null, $resize_callback)->save($image_path, $quality);
    }


    public function changeImageQuality(UploadedFile $file, int $quality, string $image_path): Image
    {
        $image = $this->imageManager->make($file)->orientate();
        return $image->save($image_path, $quality);
    }

    /**
     * @param UploadedFile $file
     * @param AuthenticatedUser $user
     * @param string $destination uploads|avatars
     * @param string|null $fileable_type
     * @param string|int|null $fileable_id
     * @return CreatedFile
     */
    public function upload(
        UploadedFile $file, AuthenticatedUser $user,
        string       $destination = 'uploads',
        string       $fileable_type = null, string|int $fileable_id = null
    ): CreatedFile
    {
        $dto = UploadedFileDto::createFromUploadedFile($file, $user->id, $fileable_type, $fileable_id);


        if (in_array($file->extension(), ['jpg', 'jpeg', 'png'])) {
            if ($destination === FileDestinations::Avatars->value) {
                $image_path = $dto->getSavePath(FileDestinations::Avatars);
                $dto->setFileableType(FileableType::AccountAvatar);
                $saved_image = $this->resizeImage($file, 650, 100, $image_path);

            } else {
                $image_path = $dto->getSavePath(FileDestinations::Uploads);
                $saved_image = $this->changeImageQuality($file, 20, $image_path);
            }

            $dto->setSize($saved_image->filesize() ?? 0);

        } else {
            $file->move(public_path(FileDestinations::Uploads->path()), $dto->getGeneratedName());
        }

        $createdFile = File::create($dto->toArray());

        return CreatedFile::from([
            'id' => $createdFile->id,
            'original_name' => $dto->getOriginalName(),
            'generated_name' => $dto->getGeneratedName(),
            'type' => $dto->getMimeType(),
            'size' => $dto->getSize(),
            'created_at' => $createdFile->created_at,
        ]);
    }
}
