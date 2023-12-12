<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IAirtimeRepository;
use App\Models\Airtime;
use App\Traits\WhereHouseTrait;

class AirtimeRepository implements IAirtimeRepository{

    use WhereHouseTrait;

    
    protected $x_secret_key;


    public function __construct()
    {
        $this->x_secret_key = env('PSG_SECRET_KEY');
    }


    public function create($data){

        /* $obj = Airtime::create($data);
        return $obj; */
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
        
        /* $objs = Airtime::with([])->where('name', $name)->get();

        return $objs; */
    }

    public function getProvider(string $id)
    {
        
        $response = $this->getProviders();
        
        if(empty($response) )
        {
            return NULL;
        }


        $providers = collect($response);

        

        $provider = $providers->where('warehouse_id', $id);

        return $provider;
    }

    public function getProviders()
    { 
        
        $requestData = [
            'header' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Secret-Key' => $this->x_secret_key,
            ],
            'url' => env('AIRTIME_LIST_ENDPOINT')
        ];

        // $list = $this->curlGetApi($requestData);
        $response = $this->httpClientGetApi($requestData);

        return $response;
    }

    public function store(Airtime $airtime){

        return $airtime->save();

    }



    
    public function update(Airtime $airtime, $data){
        
        $airtime->update($data);

        return $airtime;
    }
    
    public function delete(Airtime $airtime){

        return $airtime->delete();
    }
    
}