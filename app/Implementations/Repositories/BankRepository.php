<?php 

namespace App\Implementations\Repositories;

use App\Enums\BankStatusEnum;
use App\Enums\VirtualAccountEnum;
use App\Interfaces\Repositories\IBankRepository;
use App\Models\Bank;
use App\Traits\StatusTrait;
use App\Traits\WhereHouseTrait;

class BankRepository implements IBankRepository{

    use StatusTrait,
        WhereHouseTrait;

    protected $bank_enquiry_endpoint;
    protected $bank_list_endpoint;
    protected $x_secret_key;


    public function __construct()
    {
        $this->bank_enquiry_endpoint = env('BANK_ENQUIRY_ENDPOINT');
        $this->bank_list_endpoint = env('BANK_LIST_ENDPOINT');
        $this->x_secret_key = env('PSG_SECRET_KEY');
    }
    
    
    public function accountEnquiry($data){


        $apiData = [
            'data' => json_encode($data),
            'header' => [
                'Accept: application/json',
                'Content-Type: application/json',
                'X-Secret-Key: '. $this->x_secret_key,
            ],
            'url' => $this->bank_enquiry_endpoint
        ];

        $response = $this->curlPostApi($apiData);

        return $response;
    }


    public function create($data){

        $obj = Bank::create($data);
        return $obj;
    }
    
    
    public function fetchAllBank()
    {

        $apiData = [
            'header'=> [
               'Accept: application/json',
               'Content-Type: application/json',
               'X-Secret-Key: '. $this->x_secret_key,
            ],
            'url'=> $this->bank_list_endpoint
            // 'url'=> env('BANK_LIST_ENDPOINT')
        ];

        $response = $this->curlGetApi($apiData);

        return $response;

    }

    
    public function get(string $id){

        // return Bank::with(['accounts'])->where('id', $id);

        $response = $this->getAll();

        if(!$response->success )
        {
            return NULL;
        }


        $banks = collect($response->data->banks);

        $bank = $banks->where('bank_code', $id);

        return $bank;
    }
    
    public function getAll(){

        // return Bank::with(['accounts'])->get();

        $apiData = [
            'header'=> [
               'Accept: application/json',
               'Content-Type: application/json',
               'X-Secret-Key: '. $this->x_secret_key,
            ],
            'url'=> env('BANK_LIST_ENDPOINT')
        ];

        $response = $this->curlGetApi($apiData);

        return $response;
    }

    public function getByName($name)
    {
        
        $objs = Bank::with([])->where('name', $name)->get();

        return $objs;
    }


    public function getVirtualAccountBanks()
    {
        
        $objs = Bank::with([])
            ->where('is_virtual_account', VirtualAccountEnum::True->value)
            ->where('is_active', BankStatusEnum::Active->value)->get();

        return $objs;
    }

    public function store(Bank $bank){

        $obj = $bank->save();

        return $obj;
    }



    
    public function update(Bank $obj, $data){
        
        $obj->update($data);

        return $obj;
    }
    
    public function delete(Bank $obj){

        return $obj->delete();
    }
    
}