<?php 


namespace App\Implementations\Services;

use App\Enums\VerificationEnum;
use App\Http\Resources\CardTypesResource;
use App\Http\Resources\UsersResource;
use App\Http\Resources\VerificationsResource;
use App\Interfaces\Repositories\ICardTypeRepository;
use App\Interfaces\Services\ICardTypeService;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Policies\UserPolicy;


class CardTypeService implements ICardTypeService
{
    use ResponseTrait;

    protected $cardTypeRepository;

    
    public function __construct( 
        ICardTypeRepository $cardTypeRepository, 
    )
    {

        $this->cardTypeRepository = $cardTypeRepository;
    }
    
    
    public function getAllCardType(){

        $card = $this->cardTypeRepository->fetchAll();

        if($card->count() < 1)
        {
            return $this->errorResponse(
                404,
                'No record found', 
            );
        }

        $resource = CardTypesResource::collection($card);

        return $this->successResponse( 
            message: 'Successful',
            data: $resource
        );

    }
    
    
    public function getCardType(string $id){
        
        if($id != Auth::id())
        {
            return $this->errorResponse(
                ['id' => 'Invalid user Id'], 
                'Validation error',
                422
            );
        }

        $user = $this->cardTypeRepository->fetch($id)->firstOrFail();

        return $this->successResponse(
            new UsersResource($user), 
            'Successful'
        );
    }

    
    
    public function storeCardType($request){


        /* DB::beginTransaction();

        // Save User
        $newUser = $this->CreateUser($request);

        // Create Wallet
        $newWallet = $this->CreateDefaultWallet($newUser, $request);

        // Create Account
        $account = $this->CreateAccount($newUser, $newWallet);


        if(!$account)
        {
            $account_no = (int)$newUser->phone_no;

            return $this->errorResponse(
                [], 
                'Account number ('. $account_no .') Already taken by another user'
            );
        }


        // Save Notification
        $notification = $this->notificationRepository->create([
            'user_id' => $newUser->id
        ]);

        
        DB::commit();

        // send notification
        

        // get created user
        $user = $this->userRepository->get($newUser->id)->firstOrFail();

        $token = Auth::login($user);


        return $this->tokenResponse(
            new UsersResource($user),
            $token,
            'Created Successful'
        ); */
    }
    
    
    
    public function updateCardType($request, $id)
    {
        $validated = $request->validated();

        $user = $this->cardTypeRepository->fetch($id)->firstOrFail();
        // $country = $this->countryRepository->get($validated['country_id'])->firstOrFail();

        $validated['profile_image'] = $validated['profile_image'] ?? $user->profile_image;
        // $validated['country'] = $country->name;
        
       
        DB::beginTransaction();

        $update = $this->cardTypeRepository->update($user, $validated);

        DB::commit();


        return $this->successResponse(
            new UsersResource($update), 
            'Updated Successful'
        );
    }


    
    public function deleteCardType(string $id){
        
        $verification = $this->cardTypeRepository->fetch($id)->firstOrFail();

        $delete = $this->cardTypeRepository->delete($verification);

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