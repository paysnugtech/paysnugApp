<?php 


namespace App\Implementations\Services;

use App\Enums\VerificationEnum;
use App\Http\Resources\CardsResource;
use App\Http\Resources\VerificationsResource;
use App\Interfaces\Repositories\IAddressRepository;
use App\Interfaces\Repositories\ICardRepository;
use App\Interfaces\Repositories\ICardTypeRepository;
use App\Interfaces\Repositories\ICountryRepository;
use App\Interfaces\Repositories\INotificationRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Repositories\IVerificationRepository;
use App\Interfaces\Services\ICardService;
use App\Models\Address;
use App\Models\Card;
use App\Traits\ResponseTrait;
use App\Traits\VerificationTrait;
use App\Traits\WhereHouseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CardService implements ICardService
{
    use ResponseTrait,
        WhereHouseTrait;

    protected $addressRepository;
    protected $cardRepository;
    protected $cardTypeRepository;
    protected $countryRepository;
    protected $notificationRepository;
    protected $profileRepository;
    protected $userRepository;
    protected $verificationRepository;
    protected $x_secret_key;

    
    public function __construct( 
        IAddressRepository $addressRepository, 
        ICardRepository $cardRepository, 
        ICardTypeRepository $cardTypeRepository, 
        ICountryRepository $countryRepository, 
        INotificationRepository $notificationRepository,
        IUserRepository $userRepository, 
        IVerificationRepository $verificationRepository
    )
    {

        $this->addressRepository = $addressRepository;
        $this->cardRepository = $cardRepository;
        $this->cardTypeRepository = $cardTypeRepository;
        $this->countryRepository = $countryRepository;
        $this->notificationRepository = $notificationRepository;
        $this->userRepository = $userRepository;
        $this->verificationRepository = $verificationRepository;
        $this->x_secret_key = env('PSG_SECRET_KEY');
    }
    
    
    
    public function list(){

        $user = $this->cardRepository->fetchAll();

        if($user->count() < 1)
        {
            return $this->ErrorResponse([], 'No record found', 404);
        }

        $resource = CardsResource::collection($user);

        return $this->successResponse($resource, 'Successful');

    }
    
    public function getCard(string $id){
        

        $card = $this->cardRepository->fetch($id)->firstOrFail();

        return $this->successResponse(
            new CardsResource($card), 
            'Successful'
        );
    }



    public function storeIdCard($request, $user)
    {
        $validated = $request->validated();
        $card_id = $validated['card_id'];

        $verification = $user->verification;

        if($verification->card->is_verified)
        {
            return $this->errorResponse(
                message: 'User ID Card already verified'
            );
        }

        
        $idFrontFile = $request->file('front');
        $idBackFile = $request->file('back') ?? NULL;

        if (!$idFrontFile->isValid()) 
        {
            return $this->errorResponse(
                422,
                'Validation Errors',
                [
                    'front' => 'Invalid front uploaded'
                ]
            );
        }


        $cardType = $this->cardTypeRepository->fetch($card_id)->firstOrFail();


        if($cardType->doc_no > 1)
        {

            if (!$idBackFile) 
            {
                return $this->errorResponse(
                    message: 'Back of the ID Card not uploaded'
                );
            }

            if (!$idBackFile->isValid()) 
            {
                return $this->errorResponse(
                    message: 'Invalid front uploaded'
                );
            }

        }
        else
        {

            $idBackFile = $idFrontFile;
        }


        $apiData = [
            'data' => json_encode([
                'doc_type' => $cardType->doc_type,
                'file1' => base64_encode(file_get_contents($idFrontFile)),
                'file2' => base64_encode(file_get_contents($idBackFile)),
                'name' => $user->id,
            ]),
            'header' => [
                'Accept: application/json',
                'Content-Type: application/json',
                'X-Secret-Key: '. $this->x_secret_key,
            ],
            'url' => env('PSG_UPLOAD_ID_CARD_URL')
        ];



        $uploadIdCard = $this->curlPostApi($apiData);

        // print_r($uploadIdCard);
        
        if(!$uploadIdCard->success)
        {
            return $this->errorResponse(
                message: $uploadIdCard->message,
                errors: $uploadIdCard->errors
            );
        }

        $verification->attempt += 1;
        $verification->updated_by = $user->id;

        
        $card = $verification->card;
        $card->number = $validated['card_number'];
        $card->front_url = $uploadIdCard->data->url1;
        $card->back_url = $uploadIdCard->data->url2 ?? NULL;
        $card->updated_by = $user->id;


        // store the bvn
        DB::beginTransaction();

        $updateVerification = $this->verificationRepository->store($verification);

        $updateCard = $this->cardRepository->store($card);

        if(!$updateCard)
        {
            return $this->errorResponse(
                message: 'Unable to update card verification'
            );
        }

        DB::commit();

        return $this->successResponse(
            message: 'Successful',
            data: new CardsResource($card) 
        );
    }


    public function updateCard($request)
    {

    }

    public function verifyCard($request, $user)
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

        $address = new Address;
        $address->city = $validated['city'];
        $address->country = $validated['country'];
        $address->postal_code = $validated['postal_code'] ?? NULL;
        $address->profile_id = $user->profile->id;
        $address->street = $validated['street'];


        // store the bvn
        DB::beginTransaction();
        
        $this->addressRepository->store($address);

        $this->verificationRepository->store($verification);

        $updateBill = $this->cardRepository->store($bill);

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


    
    public function removeCard(string $id){
        
        $card = $this->cardRepository->fetch($id)->firstOrFail();

        $delete = $this->cardRepository->delete($card);

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