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

        $log = new Log;
        $log->ip = $request->ip();
        $log->login_at = now();
        $log->status = LogStatusEnum::Login->value;
        $log->user_id = $user->id;

        $getLog = $this->logRepository->getByUserId($user->id)->first();

        if($getLog)
        {
            $this->logRepository->delete($getLog);
        }

        $store = $this->logRepository->save($log);

        return $store; 
    }
}