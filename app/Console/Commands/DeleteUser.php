<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class DeleteUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete {userId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safely (as possible) remove a user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = intval($this->argument('userId'));
        $user = User::find($userId);

        if(!$user) {
            $this->warn("User with id [{$userId}] not found.");
            return;
        }

        $profile = $user->profile;

        if($profile) {
            $profile->assignSubjects([]);
            $profile->delete();
        }
        
        $user->delete();
    }
}
