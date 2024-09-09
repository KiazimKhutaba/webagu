<?php

namespace App\Modules\Authentication\Dtos;


use App\Common\DtoInterface;
use App\Common\Enums\UserRole;
use App\Common\UserStatus;

class CreateAccountDto implements DtoInterface
{
    private string $firstname;
    private string $lastname;
    private string $department;
    private string $phone;
    private string $email;
    private string $password;
    private UserRole $role;
    private UserStatus $status;


    public static function from(array $data): static
    {
        return (new self())
            ->setFirstname($data['firstname'])
            ->setLastname($data['lastname'])
            ->setDepartment($data['department'])
            ->setPhone($data['phone'])
            ->setEmail($data['email'])
            ->setPassword($data['password'])
            ->setRole(UserRole::from($data['role'] ?? UserRole::Student->value))
            ->setStatus(UserStatus::from($data['status'] ?? UserStatus::NotActivated->value));
    }

    public function toArray(): array
    {
        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'department' => $this->department,
            'phone' => $this->phone,
            'email' => $this->email,
            'password' => $this->password,
            'status' => $this->status->value,
            'role' => $this->role->value
        ];
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function setRole(UserRole $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getStatus(): UserStatus
    {
        return $this->status;
    }

    public function setStatus(UserStatus $status): self
    {
        $this->status = $status;
        return $this;
    }


}
