<?php

namespace App\Modules\File\Services;

use App\Common\Enums\FileableType;
use App\Modules\File\Dtos\CkEditorFileUploaded;
use App\Modules\File\Models\File;
use Illuminate\Http\UploadedFile;

class CKEditorFileUploadService
{
    private const DESTINATION = 'upload';


    public function upload(UploadedFile $uploadedFile, int $user_id, ?int $fileable_id = null): CkEditorFileUploaded
    {
        /*if (in_array($uploadedFile->extension(), ['jpg', 'jpeg', 'png'])) {

        }*/

        $clientOriginalName = $uploadedFile->getClientOriginalName();

        $fileName = sprintf('%s.%s', md5($clientOriginalName . microtime(true)), $uploadedFile->extension());

        $type = $uploadedFile->getClientMimeType();
        $size = $uploadedFile->getSize();

        $data = [
            'user_id' => $user_id,
            'original_name' => $clientOriginalName,
            'generated_name' => $fileName,
            'type' => $type,
            'size' => $size,
            'fileable_type' => FileableType::LectureFile,
            'fileable_id' => $fileable_id
        ];

        File::create($data);
        $uploadedFile->move(public_path(self::DESTINATION), $fileName);

        return CkEditorFileUploaded::from($fileName);

    }
}
