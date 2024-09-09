<?php

namespace App\Modules\Account\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Account\Http\Requests\ProfileAvatarUpdateRequest;
use App\Modules\Account\Services\UserActionsLoggingService;
use App\Modules\Authentication\Http\Requests\CreateAccountFormRequest;
use App\Modules\Authentication\Services\AuthenticationService;
use App\Modules\File\Services\FileUploadService;
use Exception;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(
        private readonly UserActionsLoggingService $loggingService,
        private readonly AuthenticationService $authenticationService,
    )
    {}


    /**
     * Store a newly created resource in storage.
     * @throws Exception
     */
    public function store(CreateAccountFormRequest $request)
    {
        $params = $request->validated();

        if($user = User::create($params)) {
            $this->loggingService->log($request, $user->id);
            return $user;
        }

        throw new Exception('Cant create user');

        //return User::create($params);
    }


    public function saveAvatar(Request $request)
    {
        $avatar = $request->post('avatar');
        return ['res' => auth()->user()->update(['avatar' => $avatar])];
    }

    public function saveProfileAvatar(ProfileAvatarUpdateRequest $request, FileUploadService $uploadService)
    {
        $auth_user = $this->authenticationService->getAuthenticatedUserX(auth());
        $avatar = $request->file('avatar');

        $user = User::find($auth_user->id);
        $uploaded = $uploadService->upload($avatar, $auth_user, 'avatars', fileable_id: $user->id);

        if($user?->update(['avatar' => $uploaded->generated_name])) {
            return ['avatar' => $uploaded->generated_name ];
        }

        return ['avatar' => null ];
    }
}
