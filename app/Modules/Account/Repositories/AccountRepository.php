<?php

namespace App\Modules\Account\Repositories;

use App\Modules\Account\Models\PasswordChange;
use DB;
use Exception;
use Hash;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountRepository
{

    public function getAll(): LengthAwarePaginator
    {
        return PasswordChange::leftJoin('users', 'users.phone', '=', 'password_changes.phone')
            ->where('users.id', '!=', 'null')
            ->select([
                'password_changes.*',
                DB::raw('CONCAT(users.lastname, \' \', users.firstname) as name'),
                'users.id as user_id'
            ])
            ->paginate();
    }

    /**
     * @throws Exception
     */
    public function createPasswordChangeRequest(array $data)
    {
        $model = PasswordChange::where([
            'phone' => $data['phone'],
            'is_approved' => 0
        ])->latest()->first();

        if($model) {
            throw new Exception("Ваша заявка уже принята и ожидает одобрения!");
        }

        $data['password'] = Hash::make($data['password']);
        $data['is_approved'] = 0;
        $model = PasswordChange::create($data);
        return $model?->id;
    }


    public function update(PasswordChange $model, array $data)
    {
        $isOk = $model->update([
            'id' => $model->id,
            ...$data
        ]);

        return ['isOk' => $isOk];
    }


    public function remove(int $id)
    {
        return PasswordChange::destroy($id);
    }
}
