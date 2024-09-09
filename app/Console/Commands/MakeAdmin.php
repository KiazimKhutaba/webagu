<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class MakeAdmin extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-admin {phone}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make user as admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $phone = $this->argument('phone');
        $user = User::where(['phone' => $phone]);

        if(!$user->exists()) {
            $this->error('User with this phone not found!');
        }
        else {
            $user->update(['role' => 'admin', 'status' => 'activated']);
            $this->info("User with phone '$phone' has gotten 'Admin' role!");
        }


    }
}
