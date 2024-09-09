<?php

namespace App\Modules\Authentication\Services;


use App\Common\UserRole;

class AuthenticatedUser
{

    public function __construct(
        public readonly int      $id,
        public readonly UserRole $role,
    )
    {
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isTeacher()
    {
        return UserRole::Teacher === $this->role;
    }

    public function isStudent()
    {
        return UserRole::Student === $this->role;
    }
}
