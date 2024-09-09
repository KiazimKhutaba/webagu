<?php

namespace App\Common\Files;

use App\Common\Enums\FileableType;
use App\Modules\File\Models\File;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;

class FileUploadService
{
    public function __construct(
        private readonly ImageManager $imageManager
    )
    {
    }

    public function uploadX(
        UploadedFile $file, int $user_id,
        ?FileableType $fileable_type = null, ?string $fileable_id = null,
        string $destination = 'uploads',
    ): CreatedFile
    {
        return $this->upload($file, $user_id,  $fileable_type?->value, $fileable_id, $destination);
    }

    /**
     * @param UploadedFile $file
     * @param int $user_id
     * @param string $destination uploads|avatars
     * @param string|null $fileable_type
     * @param string|int|null $fileable_id
     * @return CreatedFile
     */
    public function upload(
        UploadedFile $file, int $user_id,
        string $fileable_type = null,
        string|int $fileable_id = null,
        string $destination = 'uploads',
    ): CreatedFile
    {
        $clientOriginalName = $file->getClientOriginalName();

        $fileName = sprintf('%s.%s', md5($clientOriginalName . microtime(true)), $file->extension());

        $type = $file->getClientMimeType();
        $size = $file->getSize();

        $array = [
            'user_id' => $user_id,
            'original_name' => $clientOriginalName,
            'generated_name' => $fileName,
            'type' => $type,
            'size' => $size,
            'fileable_type' => $fileable_type,
            'fileable_id' => $fileable_id
        ];


        if (in_array($file->extension(), ['jpg', 'jpeg', 'png'])) {
            $image = $this->imageManager->make($file)->orientate();

            if ('avatars' === $destination) {
                $image_path = public_path('images/avatars') . DIRECTORY_SEPARATOR . $fileName;
                $array['object_source'] = 'account.avatar';
                $saved_image = $image->resize(650, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($image_path, 100);

            } else {
                $image_path = public_path('upload') . DIRECTORY_SEPARATOR . $fileName;
                $saved_image = $image->save($image_path, 20);
            }

            $array['size'] = $saved_image?->filesize() ?? 0;

        } else {
            $file->move(public_path('upload'), $fileName);
        }

        $createdFile = File::create($array);

        return CreatedFile::from([
            'id' => $createdFile->id,
            'original_name' => $clientOriginalName,
            'generated_name' => $fileName,
            'type' => $type,
            'size' => $array['size'],
            'created_at' => $createdFile->created_at,
        ]);
    }
}
