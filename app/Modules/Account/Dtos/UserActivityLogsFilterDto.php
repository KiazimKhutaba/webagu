<?php

namespace App\Modules\Account\Dtos;

use App\Common\Dtos\RequestDtoInterface;

class UserActivityLogsFilterDto implements RequestDtoInterface
{


    public function __construct
    (
        public readonly array $users,
        public readonly array $actions,
        public readonly array $ips,
        public readonly array $urls,
        public readonly string $start_dt,
        public readonly string $end_dt,
    )
    {}

    public static function from(array $input): self
    {
        return new self(
            $input['users'] ?? [],
            $input['actions'] ?? [],
            $input['ips'] ?? [],
            $input['urls'] ?? [],
            $input['start_dt'] ?? '',
            $input['end_dt'] ?? '',
        );
    }

    public function rules(): array
    {
        return [];
    }

    public function toArray(): array
    {
        return [
            'users' => $this->users,
            'actions' => $this->actions,
            'ips' => $this->ips,
            'urls' => $this->urls,
            'start_dt' => $this->start_dt,
            'end_st' => $this->end_dt,
        ];
    }
}
