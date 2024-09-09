<?php

namespace App\Modules\Account\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Account\Http\Requests\ChagePasswordRequestApproveStatusRequest;
use App\Modules\Account\Http\Requests\PasswordChangeRequest;
use App\Modules\Account\Models\PasswordChange;
use App\Modules\Account\Repositories\AccountRepository;

class RequestsController extends Controller
{

    public function __construct(
        private readonly AccountRepository $accountRepository
    )
    {}


    public function index()
    {
        return $this->accountRepository->getAll();
    }


    /**
     * @throws \Exception
     */
    public function passwordChangeRequest(PasswordChangeRequest $request)
    {
        return ['id' => $this->accountRepository->createPasswordChangeRequest($request->validated())];
    }


    public function updateApproveStatus(ChagePasswordRequestApproveStatusRequest $request, PasswordChange $model)
    {
        // Todo: this logic can be changed
        $user = User::where(['phone' => $model->phone])->first();
        $user->password = $model->password;
        $user->save();

        return $this->accountRepository->update($model, $request->validated());
    }


    public function destroy(int $id)
    {
        return $this->accountRepository->remove($id);
    }
}
