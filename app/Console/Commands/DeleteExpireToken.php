<?php

namespace App\Console\Commands;


use App\Interfaces\Repositories\ITokenRepository;
use App\Models\User;
use App\Traits\TokenTrait;
use Illuminate\Console\Command;

class DeleteExpireToken extends Command
{

    use TokenTrait;

    protected $tokenRepository;

    public function __construct(ITokenRepository $tokenRepository)
    {
        parent::__construct();
        $this->tokenRepository = $tokenRepository;
    }



    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-expire-token';

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
        $delete_count = 0;
        $tokens = $this->tokenRepository->fetchAll();

        foreach($tokens as $token)
        {

            if($this->TokenExpire($token))
            {
                $this->tokenRepository->delete($token);

                $delete_count++;
            }
        }

        

        $this->info("Successfully deleted ($delete_count) expired token");
    }
}
