<?php

namespace App\Modules\File\Enums;

enum FileDestinations: string
{
    case Avatars = 'avatars';
    case Uploads = 'upload';


    public function path(): string
    {
        return match ($this) {
            self::Avatars => 'images/avatars',
            self::Uploads => 'upload',
        };
    }
}
