<?php 

namespace App\Traits;
use App\Interfaces\Repositories\IVerificationRepository;

trait StoreVerification{

    protected $verificationRepository;


    public function __construct(
        IVerificationRepository $verificationRepository
    )
    {
        $this->verificationRepository = $verificationRepository;
    }

    
    
    protected function storeVerification($user)
    { 

        // Save Notification
        $verification = $this->verificationRepository->create([
            'user_id' => $user->id
        ]);


        $verification->bill()->create([
            'user_id' => $user->id
        ]);

        $verification->bvn()->create([
            'user_id' => $user->id
        ]);

        $verification->card()->create([
            'user_id' => $user->id
        ]);

        return $verification; 
    }
}