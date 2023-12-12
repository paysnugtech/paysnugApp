<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IElectricityRepository;
use App\Models\Electricity;
use App\Traits\StatusTrait;
use App\Traits\WhereHouseTrait;

class ElectricityRepository implements IElectricityRepository{

    use StatusTrait, WhereHouseTrait;

    protected $data_commission;
    protected $provider_endpoint;
    protected $package_endpoint;
    protected $verify_endpoint;
    protected $pay_bill_endpoint;
    protected $x_secret_key;


    public function __construct()
    {
        $this->data_commission = env('DATA_COMMISSION');
        $this->provider_endpoint = env('ELECTRICITY_PROVIDER_ENDPOINT');
        $this->package_endpoint = env('ELECTRICITY_PACKAGE_ENDPOINT');
        $this->verify_endpoint = env('CUSTOMER_VERIFY_ENDPOINT');
        $this->pay_bill_endpoint = env('PAY_BILL_ENDPOINT');
        $this->x_secret_key = env('PSG_SECRET_KEY');
    }


    public function create($data){

        /* $obj = Airtime::create($data);
        return $obj; */
    }
    
    public function fetch(string $id){

        return Electricity::with(['user'])->where('id', $id);

    }
    
    public function fetchAll(){

        return Electricity::with(['user'])->get();
    }

    public function fetchByName($name)
    {
        
        /* $objs = Airtime::with([])->where('name', $name)->get();

        return $objs; */
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


        $response = $this->curlPostApi($apiData);


        return $response;
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

    public function store(Electricity $electricity){

        return $electricity->save();

    }



    
    public function update(Electricity $electricity, $data){
        
        $electricity->update($data);

        return $electricity;
    }
    
    public function delete(Electricity $electricity){

        return $electricity->delete();
    }
    
}