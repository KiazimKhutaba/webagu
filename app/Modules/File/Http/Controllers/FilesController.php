<?php

namespace App\Modules\File\Http\Controllers;

use App\Common\Enums\FileableType;
use App\Http\Controllers\Controller;
use App\Modules\File\Http\Requests\StoreCkEditorFileRequest;
use App\Modules\File\Http\Requests\StoreFileRequest;
use App\Modules\File\Models\File;
use App\Modules\File\Models\ImageMeta;
use App\Modules\File\Services\CKEditorFileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use function in_array;


class FilesController extends Controller
{
    public function __construct(
        private readonly CKEditorFileUploadService $ckEditorFileUploadService
    )
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return File::with('user')->where('fileable_type', '!=', 'account.avatar')->paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFileRequest $request, ImageManager $imageManager)//: JsonResponse
    {
        $dest = $request->get('dest', 'uploads');
        $fileable_type = $request->get('fileable_type');
        $file = $request->file('file');

        $clientOriginalName = $file->getClientOriginalName();
        $fileName = sprintf('%s.%s', md5($clientOriginalName . microtime(true)), $file->extension());

        $type = $file->getClientMimeType();
        $size = $file->getSize();

        $array = [
            'user_id' => auth()->id(),
            'original_name' => $clientOriginalName,
            'generated_name' => $fileName,
            'type' => $type,
            'size' => $size
        ];

        $image_meta = [
            'generated_name' => $fileName,
            'user_id' => auth()->id(),
            'dest' => 'uploads'
        ];

        if (in_array($file->extension(), ['jpg', 'jpeg', 'png']))
        {
            $image = $imageManager->make($file)->orientate();
            $image_meta['exif'] = $image->exif();

            if ('avatars' === $dest)
            {
                $image_path = public_path('images/avatars') . DIRECTORY_SEPARATOR . $fileName;
                $array['fileable_type'] = FileableType::AccountAvatar;
                $saved_image = $image->resize(400, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($image_path, 100);
                $image_meta['dest'] = 'avatars';
            }
            else
            {
                $array['fileable_type'] = $fileable_type;
                $image_path = public_path('upload') . DIRECTORY_SEPARATOR . $fileName;
                $saved_image = $image->save($image_path, 10);
            }

            $array['size'] = $saved_image?->filesize() ?? 0;

            ImageMeta::create($image_meta);
        }
        else
        {
            $file->move(public_path('upload'), $fileName);
        }


        $createdFile = File::create($array);
        $user = auth()->user();

        return response()->json([
            'id' => $createdFile->id,
            'original_name' => $clientOriginalName,
            'generated_name' => $fileName,
            'type' => $type,
            //'exif' => $array['exif'],
            'size' => $array['size'],
            'created_at' => $createdFile->created_at,
            'user' => [
                'lastname' => $user->lastname,
                'firstname' => $user->firstname
            ],
            'fileable_type' => $createdFile->fileable_type,
        ]);
    }

    public function search(Request $request)
    {
        $filename = $request->get('filename');

        return File::where('original_name', 'LIKE', "%$filename%")->get();
    }


    public function ckeditorFileUpload(StoreCkEditorFileRequest $request)
    {
        $file = $request->file('upload');
        $upload = $this->ckEditorFileUploadService->upload($file, auth()->id());
        return new JsonResponse($upload->toArray());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file): array
    {
        if ($file->fileable_type == 'account.avatar') {
            $path2File = public_path('/images/avatars/' . $file->generated_name);
        } else {
            $path2File = public_path('/upload/' . $file->generated_name);
        }

        unlink($path2File);

        return ['status' => $file->delete()];
    }
}
