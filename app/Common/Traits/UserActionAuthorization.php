<?php

namespace App\Common\Traits;

use App\Common\Exceptions\ForbiddenException;
use Illuminate\Http\Request;

trait UserActionAuthorization
{
    /**
     * @throws ForbiddenException
     */
    private function authorizeCustomerAction(Request $request, int $customer_id): void
    {
        $user = $this->authenticationService->getUser($request->bearerToken());

        if($user->isCustomer()) {
            if($user->id !== $customer_id) {
                throw new ForbiddenException("Заказ не принадлежит клиенту");
            }
        }
    }
}