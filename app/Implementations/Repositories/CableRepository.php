<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\ICableRepository;
use App\Models\Cable;
use App\Traits\StatusTrait;
use App\Traits\WhereHouseTrait;

class CableRepository implements ICableRepository{

    use StatusTrait, 
        WhereHouseTrait;

    protected $data_commission;
    protected $provider_endpoint;
    protected $package_endpoint;
    protected $verify_endpoint;
    protected $pay_bill_endpoint;
    protected $x_secret_key;


    public function __construct()
    {
        $this->data_commission = env('DATA_COMMISSION');
        $this->provider_endpoint = env('CABLE_PROVIDER_ENDPOINT');
        $this->package_endpoint = env('CABLE_PACKAGE_ENDPOINT');
        $this->verify_endpoint = env('CUSTOMER_VERIFY_ENDPOINT');
        $this->pay_bill_endpoint = env('PAY_BILL_ENDPOINT');
        $this->x_secret_key = env('PSG_SECRET_KEY');
    }


    public function create($data){

        $obj = Cable::create($data);
        return $obj;
    }
    
    public function fetch(string $id){

        return Cable::with(['user'])->where('id', $id);

    }
    
    public function fetchAll(){

        return Cable::with(['user'])->get();
    }
    
    public function fetchByCustomerId($customer_id)
    {

        return Cable::with(['user'])->where('customer_id', $customer_id);

    }
    
    public function fetchByUserId($user_id)
    {

        return Cable::with(['user'])->where('user_id', $user_id);

    }


    public function fetchCustomer($data)
    { 
        
        $requestData = [
            "data"=> json_encode($data),
            "header"=> [
                "Accept: application/json",
                "Content-Type: application/json",
                "X-Secret-Key: ". $this->x_secret_key
            ],
            "url" => $this->verify_endpoint
        ];


        $response = $this->curlPostApi($requestData);


        return $response;
    }


    public function fetchPackages($data)
    {

        $apiData = [
            'data' => json_encode([
                "_id" => $data->_id,
                "name" => $data->name,
                "code" => $data->code,
                "provider_id" => $data->provider_id,
                "others" => $data->others
            ]),
            'header' => [
                'Accept: application/json',
                'Content-Type: application/json',
                'X-Secret-Key: '. $this->x_secret_key,
            ],
            'url' => $this->package_endpoint,
        ];


        $packages = $this->curlPostApi($apiData);

        // print_r($packages);

        return $packages;
    }



    public function fetchProvider(string $id)
    {
        
        $response = $this->fetchProviders();
        
        if(empty($response) )
        {
            return NULL;
        }


        $providers = collect($response);

        

        $provider = $providers->where('warehouse_id', $id);

        return $provider;
    }

    public function fetchProviders()
    { 
        
        $requestData = [
            'header' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Secret-Key' => $this->x_secret_key,
            ],
            'url' => $this->provider_endpoint
        ];

        // $list = $this->curlGetApi($requestData);
        $response = $this->httpClientGetApi($requestData);


        return $response;
    }

    public function store(Cable $cable){

        return $cable->save();

    }



    
    public function update(Cable $cable, $data){
        
        $cable->update($data);

        return $cable;
    }
    
    
    public function delete(Cable $cable){

        return $cable->delete();
    }
    
}