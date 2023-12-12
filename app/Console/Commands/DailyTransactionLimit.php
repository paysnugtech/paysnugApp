<?php

namespace App\Console\Commands;


use App\Interfaces\Repositories\IUserRepository;
use App\Models\User;
use Illuminate\Console\Command;

class DailyTransactionLimit extends Command
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
    protected $signature = 'app:daily-transaction-limit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset daily transaction limit for each user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::All();

        foreach($users as $user)
        {
            
            $user->level->daily_unused = $user->level->daily_limit;

            $user->level()->update([
                "daily_unused" => $user->level->daily_unused
            ]);
        }

        $this->info("Successfully reset user daily transaction limit");
    }
}
