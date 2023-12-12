<?php 

namespace App\Traits;
use App\Enums\LogStatusEnum;
use App\Interfaces\Repositories\ILogRepository;
use App\Models\Log;

trait StoreLog{

    protected $logRepository;


    public function __construct(
        ILogRepository $logRepository
    )
    {
        $this->logRepository = $logRepository;
    }

    
    
    protected function StoreLog($user, $request)
    { 

        $getLog = $this->logRepository->getByUserId($user->id)->first();

        if($getLog)
        {
            $timeNow = now();
            $getLog->logout_at = $timeNow;
            $getLog->deleted_at = $timeNow;

            $this->logRepository->store($getLog);
        }

        $log = new Log;
        $log->ip = $request->ip();
        $log->login_at = now();
        $log->status = LogStatusEnum::Login->value;
        $log->user_id = $user->id;

        $store = $this->logRepository->store($log);

        return $store; 
    }
}