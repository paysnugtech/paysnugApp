<?php

namespace App\Console\Commands;


use App\Interfaces\Repositories\IUserRepository;
use App\Models\User;
use Illuminate\Console\Command;

class CheckTransactionStatus extends Command
{



    public function __construct()
    {
        parent::__construct();
    }



    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:monthly-transaction-limit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset monthly transaction limit for each user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::All();

        foreach($users as $user)
        {

            $user->level()->update([
                "monthly_unused" => $user->level->monthly_limit
            ]);
        }

        $this->info("Successfully reset user monthly transaction limit");
    }
}
