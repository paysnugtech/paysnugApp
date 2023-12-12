<?php 


namespace App\Implementations\Services;

use App\Enums\VerificationEnum;
use App\Http\Resources\BillsResource;
use App\Http\Resources\BvnsResource;
use App\Http\Resources\VerificationsResource;
use App\Interfaces\Repositories\IBvnRepository;
use App\Interfaces\Repositories\ICountryRepository;
use App\Interfaces\Repositories\INotificationRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Repositories\IVerificationRepository;
use App\Interfaces\Services\IBvnService;
use App\Traits\ResponseTrait;
use App\Traits\StatusTrait;
use App\Traits\WhereHouseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class BvnService implements IBvnService
{
    use ResponseTrait, 
        StatusTrait,
        WhereHouseTrait;


    protected $bvnRepository;
    protected $countryRepository;
    protected $notificationRepository;
    protected $profileRepository;
    protected $userRepository;
    protected $verificationRepository;

    
    public function __construct(  
        IBvnRepository $bvnRepository, 
        ICountryRepository $countryRepository, 
        INotificationRepository $notificationRepository,
        IUserRepository $userRepository, 
        IVerificationRepository $verificationRepository
    )
    {

        $this->bvnRepository = $bvnRepository;
        $this->countryRepository = $countryRepository;
        $this->notificationRepository = $notificationRepository;
        $this->userRepository = $userRepository;
        $this->verificationRepository = $verificationRepository;
    }
    
    
    
    public function list(){

        $user = $this->bvnRepository->fetchAll();

        if($user->count() < 1)
        {
            return $this->ErrorResponse([], 'No record found', 404);
        }

        $resource = BillsResource::collection($user);

        return $this->successResponse($resource, 'Successful');

    }
    
    public function getBvn(string $id){
        

        $bill = $this->bvnRepository->fetch($id)->firstOrFail();

        return $this->successResponse(
            new BillsResource($bill), 
            'Successful'
        );
    }


    public function storeBvn($request, $user)
    {
        $validated = $request->validated();

        $bvn = $user->verification->bvn;

        if ($bvn->is_verified) 
        {
            return $this->errorResponse(
                message: 'Bvn already verified'
            );
        }



        $requestData = [
            'data' => json_encode([
                'bvn' => $validated['bvn'],
                'type' => "1",
            ]),
            'header' => [
                'Accept: application/json',
                'Content-Type: application/json',
                'X-Secret-Key: '. env('PSG_SECRET_KEY')
            ],
            'url' => env('BVN_ENDPOINT')
        ];


        $bvnUpload = $this->curlPostApi($requestData);


        if(!$bvnUpload->success)
        {
            return $this->errorResponse(
                message: $bvnUpload->message,
                errors: $bvnUpload->errors,
            );
        }


        print_r($bvnUpload);
        dd();


        // store the bvn
        DB::beginTransaction();
        

        $storeBvn = $this->bvnRepository->store($bvn);

        if(!$storeBvn)
        {
            return $this->errorResponse(
                message: 'Unable to store bvn' 
            );
        }

        DB::commit();

        return $this->successResponse(
            message: 'Successful',
            data: new BvnsResource($bvn), 
        );
    }


    public function updateBvn($request)
    {

    }

    public function verifyBvn($request, $user)
    {
        $validated = $request->validated();

        $verification = $user->verification;

        if (!$request->file('bill')->isValid()) 
        {
            return $this->errorResponse(
                [], 
                'Invalid bill uploaded'
            );
        }

        $uploadFile = $request->file('bill');

        if($verification->bill->is_verified)
        {
            return $this->errorResponse(
                [], 
                'User utility bill already verified'
            );
        }



        // Verify the bill
        $data = [
            'doc_type' => 'utility',
            'file' => base64_encode(file_get_contents($uploadFile)),
            'name' => $user->id,
        ];



        $uploadBill = $this->uploadUtilityBill($data);
        // $uploadBill = $this->uploadBill1($data);


        if(!$uploadBill->success)
        {
            return $this->errorResponse(
                [], 
                $uploadBill->message
            );
        }

        $verification->attempt += 1;
        $verification->updated_by = $user->id;

        
        $bill = $verification->bill;
        $bill->url = $uploadBill->data->url;
        $bill->updated_by = $user->id;



        // store the bvn
        DB::beginTransaction();

        $this->verificationRepository->store($verification);

        $updateBill = $this->bvnRepository->store($bill);

        if(!$updateBill)
        {
            return $this->errorResponse(
                [], 
                'Unable to update bill'
            );
        }

        DB::commit();

        return $this->successResponse(
            new VerificationsResource($verification), 
            'Successful'
        );
    }


    
    public function removeBvn(string $id){
        
        $bill = $this->bvnRepository->fetch($id)->firstOrFail();

        $delete = $this->bvnRepository->delete($bill);

        if(!$delete)
        {
            return $this->errorResponse(
                [], 
                'Something went wrong, User not Deleted|'
            );
        }

        return $this->successResponse(
            [], 
            'Deleted Successful'
        );
    }
    
}